composer install 

# phpmd
* .\vendor\bin\phpmd.bat .\main\ text codesize,unusedcode,naming
* https://phpmd.org/documentation/index.html

# phpcs 
* http://pear.php.net/package/PHP_CodeSniffer
* .\vendor\bin\phpcs.bat .\main\

# phpsa 
* https://github.com/ovr/phpsa
* .\vendor\bin\phpsa check .\main\
