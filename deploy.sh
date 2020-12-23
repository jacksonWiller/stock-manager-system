rm -f deploy.zip
zip deploy.zip -r * .[^.]* -x "var/cache/*" ".git/*" ".idea/*"
