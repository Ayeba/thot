#!/bin/sh

phpdoc -d ../ --target ./html/ -o HTML:Smarty:PHP --title Toth
phpdoc -d ../ --target ./pdf/ -o PDF:default:default --title Toth
