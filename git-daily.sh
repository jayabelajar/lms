#!/usr/bin/env bash
# git-daily.sh — workflow solo dev (bikin ijo) yang tinggal jalanin
# Pakai: taruh file ini di root project, lalu:
#   chmod +x git-daily.sh
#   ./git-daily.sh
#
# Fitur:
# - auto init repo kalau belum
# - pastikan remote "origin" ada (nanya sekali kalau belum)
# - pilih mode: quick push ke branch sekarang, atau buat branch feature/fix, atau merge ke main
# - commit message pakai Conventional Commits (feat/fix/chore/docs/refactor/style/test/perf/build/ci)
# - aman: gak commit kalau gak ada perubahan

set -euo pipefail

# -------- helpers --------
say() { printf "\n\033[1m%s\033[0m\n" "$*"; }
warn() { printf "\n\033[33m%s\033[0m\n" "$*"; }
err() { printf "\n\033[31m%s\033[0m\n" "$*"; }
need() { command -v "$1" >/dev/null 2>&1 || { err "Butuh '$1' tapi gak ketemu di PATH."; exit 1; }; }

need git

# -------- ensure repo --------
if ! git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  say "Belum ada git repo di folder ini. Init dulu..."
  git init
fi

# -------- ensure user config (optional) --------
if ! git config user.name >/dev/null 2>&1; then
  warn "Git user.name belum diset (opsional tapi disaranin)."
fi
if ! git config user.email >/dev/null 2>&1; then
  warn "Git user.email belum diset (opsional tapi disaranin)."
fi

# -------- ensure remote origin --------
ORIGIN_URL="$(git remote get-url origin 2>/dev/null || true)"
if [[ -z "${ORIGIN_URL}" ]]; then
  warn "Remote 'origin' belum ada."
  read -r -p "Masukin URL repo GitHub (https://github.com/user/repo.git): " url
  if [[ -z "${url}" ]]; then
    err "URL kosong. Batal."
    exit 1
  fi
  git remote add origin "$url"
  ORIGIN_URL="$url"
  say "Remote origin diset: $ORIGIN_URL"
fi

# -------- detect current branch --------
CURRENT_BRANCH="$(git symbolic-ref --short -q HEAD 2>/dev/null || true)"
if [[ -z "${CURRENT_BRANCH}" ]]; then
  # repo baru, belum ada commit
  CURRENT_BRANCH="main"
fi

# -------- menu --------
say "Pilih workflow:"
echo "1) Quick: commit + push ke branch saat ini ($CURRENT_BRANCH)  [paling cepat, bikin ijo]"
echo "2) Start feature/fix branch: bikin branch baru (feature/xxx atau fix/xxx), lalu commit+push"
echo "3) Merge: merge branch saat ini -> main, lalu push main (buat rilis kecil)"
echo "4) Setup: buat/switch ke branch develop (sekali di awal kalau mau model tim)"
echo "5) Status: tampilkan status repo"
read -r -p "Pilihan (1-5): " MODE

# -------- functions --------
ensure_main_exists() {
  # pastikan main ada
  if git show-ref --verify --quiet refs/heads/main; then
    return 0
  fi
  if git show-ref --verify --quiet refs/heads/master; then
    warn "Branch 'main' belum ada, tapi ada 'master'. Akan dipakai 'master' sebagai main."
    return 0
  fi
  # belum ada branch sama sekali (repo baru)
  git checkout -b main >/dev/null 2>&1 || true
}

main_branch_name() {
  if git show-ref --verify --quiet refs/heads/main; then
    echo "main"
  elif git show-ref --verify --quiet refs/heads/master; then
    echo "master"
  else
    echo "main"
  fi
}

has_changes() {
  # ada staged atau unstaged
  [[ -n "$(git status --porcelain)" ]]
}

commit_and_push() {
  local branch
  branch="$(git symbolic-ref --short -q HEAD 2>/dev/null || true)"
  if [[ -z "${branch}" ]]; then
    # detached or no commits
    branch="$(main_branch_name)"
  fi

  if ! has_changes; then
    warn "Gak ada perubahan untuk di-commit. (Repo clean)"
    say "Biar tetap ijo, minimal harus ada perubahan file. ✍️"
    exit 0
  fi

  say "Pilih tipe commit (Conventional Commits):"
  echo "1) feat     2) fix      3) chore   4) docs"
  echo "5) refactor 6) style    7) test    8) perf"
  echo "9) build    10) ci      11) custom"
  read -r -p "Tipe (1-11): " T

  local type="chore"
  case "${T}" in
    1) type="feat" ;;
    2) type="fix" ;;
    3) type="chore" ;;
    4) type="docs" ;;
    5) type="refactor" ;;
    6) type="style" ;;
    7) type="test" ;;
    8) type="perf" ;;
    9) type="build" ;;
    10) type="ci" ;;
    11)
      read -r -p "Ketik tipe custom (misal: wip): " type
      type="${type:-chore}"
      ;;
    *) type="chore" ;;
  esac

  read -r -p "Scope (opsional, contoh: api, ui, auth) [enter untuk skip]: " scope
  read -r -p "Pesan singkat (wajib, contoh: tambah CRUD kategori): " msg

  if [[ -z "${msg}" ]]; then
    err "Pesan commit wajib diisi."
    exit 1
  fi

  local full
  if [[ -n "${scope}" ]]; then
    full="${type}(${scope}): ${msg}"
  else
    full="${type}: ${msg}"
  fi

  say "Stage file..."
  git add -A

  # pastikan ada staged (kadang status porcelain ada tapi semuanya untracked? tetap aman)
  if git diff --cached --quiet; then
    warn "Tidak ada perubahan staged setelah git add."
    exit 0
  fi

  say "Commit: $full"
  git commit -m "$full"

  say "Push ke origin/$branch"
  # set upstream kalau belum
  if ! git rev-parse --abbrev-ref --symbolic-full-name "@{u}" >/dev/null 2>&1; then
    git push -u origin "$branch"
  else
    git push
  fi

  say "✅ Done. GitHub kamu harusnya ijo kalau ini commit baru."
}

start_branch() {
  read -r -p "Jenis branch? (feature/fix) [default: feature]: " kind
  kind="${kind:-feature}"
  if [[ "${kind}" != "feature" && "${kind}" != "fix" ]]; then
    warn "Jenis tidak dikenal, pakai 'feature'."
    kind="feature"
  fi

  read -r -p "Nama singkat branch (contoh: kategori-produk): " name
  if [[ -z "${name}" ]]; then
    err "Nama branch wajib."
    exit 1
  fi

  local b="${kind}/${name}"

  say "Switch ke base branch (develop kalau ada, kalau nggak ya main)..."
  if git show-ref --verify --quiet refs/heads/develop; then
    git checkout develop
  else
    ensure_main_exists
    git checkout "$(main_branch_name)"
  fi

  say "Buat branch: $b"
  git checkout -b "$b"

  commit_and_push
}

merge_to_main() {
  ensure_main_exists
  local MAIN
  MAIN="$(main_branch_name)"
  local FROM
  FROM="$(git symbolic-ref --short -q HEAD 2>/dev/null || true)"

  if [[ -z "${FROM}" ]]; then
    err "Kamu lagi di detached HEAD. Checkout ke branch dulu."
    exit 1
  fi

  if [[ "${FROM}" == "${MAIN}" ]]; then
    warn "Kamu sudah di branch ${MAIN}. Tidak ada yang di-merge."
    exit 0
  fi

  # Optional: push branch terbaru dulu biar aman
  say "Push branch sumber dulu (biar aman backup remote)..."
  if ! git rev-parse --abbrev-ref --symbolic-full-name "@{u}" >/dev/null 2>&1; then
    git push -u origin "$FROM"
  else
    git push
  fi

  say "Checkout ${MAIN} dan merge dari ${FROM}"
  git checkout "$MAIN"
  # kalau belum ada upstream main, set
  if ! git rev-parse --abbrev-ref --symbolic-full-name "@{u}" >/dev/null 2>&1; then
    git push -u origin "$MAIN" || true
  fi

  git merge --no-ff "$FROM" -m "chore: merge ${FROM} -> ${MAIN}"

  say "Push ${MAIN}"
  git push

  say "Opsional: hapus branch ${FROM}?"
  read -r -p "Hapus branch lokal+remote? (y/N): " del
  if [[ "${del}" == "y" || "${del}" == "Y" ]]; then
    git branch -d "$FROM" || true
    git push origin --delete "$FROM" || true
    say "Branch ${FROM} dihapus."
  fi

  say "✅ Merge selesai."
}

setup_develop() {
  ensure_main_exists
  local MAIN
  MAIN="$(main_branch_name)"

  if git show-ref --verify --quiet refs/heads/develop; then
    say "Branch develop sudah ada. Checkout develop..."
    git checkout develop
  else
    say "Buat branch develop dari ${MAIN}..."
    git checkout "$MAIN"
    git checkout -b develop
  fi

  say "Push develop dan set upstream"
  git push -u origin develop
  say "✅ develop siap. Workflow kamu bisa: feature/* -> PR/merge ke develop -> merge ke main."
}

show_status() {
  say "Repo: $(pwd)"
  echo "Remote origin: $(git remote get-url origin)"
  echo "Branch: $(git symbolic-ref --short -q HEAD 2>/dev/null || echo '(no branch)')"
  echo
  git status
}

# -------- run mode --------
case "${MODE}" in
  1) commit_and_push ;;
  2) start_branch ;;
  3) merge_to_main ;;
  4) setup_develop ;;
  5) show_status ;;
  *) warn "Pilihan gak valid. Keluar." ;;
esac