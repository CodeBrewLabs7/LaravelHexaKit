#!/bin/bash

# Set COMPOSER_ALLOW_SUPERUSER=1
export COMPOSER_ALLOW_SUPERUSER=1

composer config --no-plugins allow-plugins.dealerdirect/phpcodesniffer-composer-installer  true

# Step 1: Install slevomat/coding-standard
echo 'Installing slevomat/coding-standard...'
composer require --dev "slevomat/coding-standard" -n --ignore-platform-reqs

# Step 2: Install PHP_CodeSniffer globally
echo 'Installing PHP_CodeSniffer globally...'
composer require "squizlabs/php_codesniffer=*" -n --ignore-platform-reqs

composer dump-autoload

# Step 3: Update PSR-4 autoload entry
echo 'Updating PSR-4 autoload entry...'

# Check if the key already exists
if ! grep -q '"SlevomatCodingStandard\\\\":' composer.json; then
  # Add the new entry
  sed -i 's|"psr-4": {|"psr-4": {\n"SlevomatCodingStandard\\\\": "vendor/slevomat/coding-standard/src/",|' composer.json
else
  echo 'PSR-4 autoload entry already exists. No changes made.'
fi
# Step 4: Create ruleset.xml
echo 'Creating ruleset.xml...'
cat << 'EOF' > ruleset.xml
<?xml version="1.0"?>
<ruleset name="MyLaravelRuleset">
    <description>My custom Laravel coding standards</description>

    <!-- Include Laravel coding standards -->
    <!-- <rule ref="Laravel"/> -->

    <!-- Include PSR-2 standard -->
    <rule ref="PSR2"/>

    <!-- OOP Principles -->
    <rule ref="SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming">
        <severity>error</severity>
    </rule>
    <rule ref="SlevomatCodingStandard.Classes.SuperfluousExceptionNaming">
        <severity>error</severity>
    </rule>
    <rule ref="SlevomatCodingStandard.Classes.SuperfluousInterfaceNaming">
        <severity>error</severity>
    </rule>
    <rule ref="SlevomatCodingStandard.Classes.SuperfluousTraitNaming">
        <severity>error</severity>
    </rule>
    <!-- <rule ref="SlevomatCodingStandard.Classes.UnusedPrivateElements">
        <severity>error</severity>
    </rule> -->

    <!-- Additional rules as needed -->

</ruleset>
EOF

# Step 5: Create pre-commit Hook
echo 'Creating pre-commit hook...'
cat << 'EOF' > .git/hooks/pre-commit
#!/bin/bash

# Run PHP_CodeSniffer
vendor/bin/phpcs --standard=ruleset.xml --extensions=php app

# If PHP_CodeSniffer command fails, prevent the commit
if [ $? -ne 0 ]; then
    echo "PHP_CodeSniffer check failed. Please fix coding standards issues."
    exit 1
fi
EOF
chmod +x .git/hooks/pre-commit

echo 'Setup complete. Coding standards check is now configured.'
