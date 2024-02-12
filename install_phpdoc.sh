
#!/bin/bash

# Download phpDocumentor.phar
wget https://github.com/phpDocumentor/phpDocumentor/releases/download/v2.9.0/phpDocumentor.phar

# Move phpDocumentor.phar to /usr/local/bin/phpdoc
mv phpDocumentor.phar /usr/local/bin/phpdoc

# Make phpdoc executable
chmod +x /usr/local/bin/phpdoc

# Run phpdoc with specified parameters
phpdoc -d ./ -t ./docs --ignore=vendor/*
