#!/bin/bash

# ===== COLORS =====
RED='\033[1;31m'
GREEN='\033[1;32m'
YELLOW='\033[1;33m'
BLUE='\033[1;34m'
CYAN='\033[1;36m'
NC='\033[0m'

# ===== BANNER =====
function banner() {
clear
echo -e "${CYAN}"
echo "====================================="
echo "   DEV GIT HELPER CLI"
echo "   GitHub Automation Tool by @jayabelajar"
echo "====================================="
echo -e "${NC}"
}

# ===== PAUSE =====
pause(){
echo ""
read -p "Press Enter to continue..."
}

# ===== START PROJECT =====
start_project(){
banner
echo -e "${GREEN}Create New Project${NC}"
echo ""

read -p "Project name: " name

mkdir "$name"
cd "$name" || exit

git init
echo "# $name" > README.md

git add .
git commit -m "Initial commit"

if command -v gh &> /dev/null
then
gh repo create "$name" --public --source=. --push
echo -e "${GREEN}GitHub repo created${NC}"
else
echo -e "${YELLOW}GitHub CLI not installed${NC}"
fi

pause
}

# ===== SAVE WORK =====
save_work(){
banner
echo -e "${GREEN}Save Work${NC}"

read -p "Commit message: " msg

git add .
git commit -m "$msg"

echo -e "${GREEN}Work saved${NC}"

pause
}

# ===== SYNC =====
sync_repo(){
banner
echo -e "${GREEN}Sync Repository${NC}"

git pull
git push

echo -e "${GREEN}Repository synced${NC}"

pause
}

# ===== STATUS =====
status_repo(){
banner
git status
pause
}

# ===== RELEASE =====
release_project(){
banner
echo -e "${GREEN}Create Release${NC}"

read -p "Version (example v1.0.0): " ver

git tag "$ver"
git push origin "$ver"

if command -v gh &> /dev/null
then
gh release create "$ver" --generate-notes
fi

echo -e "${GREEN}Release created${NC}"

pause
}

# ===== CLEAN =====
clean_project(){
banner
echo -e "${YELLOW}Cleaning project...${NC}"

rm -rf node_modules
rm -rf vendor
rm -rf build
rm -rf dist

echo -e "${GREEN}Project cleaned${NC}"

pause
}

# ===== INFO =====
project_info(){
banner
echo -e "${BLUE}Project Info${NC}"
echo ""

git remote -v
echo ""
git branch
echo ""
git log -1 --oneline

pause
}

# ===== MAIN MENU =====
while true
do
banner

echo -e "${CYAN}Select Menu:${NC}"
echo ""
echo "1) Start New Project"
echo "2) Project Status"
echo "3) Save Work (Commit)"
echo "4) Sync Repo (Pull + Push)"
echo "5) Create Release"
echo "6) Clean Project"
echo "7) Project Info"
echo "0) Exit"
echo ""

read -p "Choose: " choice

case $choice in
1) start_project ;;
2) status_repo ;;
3) save_work ;;
4) sync_repo ;;
5) release_project ;;
6) clean_project ;;
7) project_info ;;
0) exit ;;
*) echo "Invalid option"; sleep 1 ;;
esac

done