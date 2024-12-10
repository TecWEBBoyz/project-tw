# Remove all the current file except this script
find . ! -name "install.sh" -exec rm -rf {} +

# Download latest repo change
curl -L -o repo.zip https://github.com/TecWEBBoyz/project-tw/archive/refs/heads/deploy.zip
unzip repo.zip

# Manage file position
mv ./project-tw-deploy/* .
rm -rf project-tw-deploy/ repo.zip