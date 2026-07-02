DirectoryIndex index.php
Options -Indexes

<FilesMatch "^(config\.php|README.*|.*\.md)$">
  Require all denied
</FilesMatch>

<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteRule ^data/ - [F,L]
</IfModule>
