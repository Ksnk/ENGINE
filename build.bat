@echo off
set PHPBIN=c:\Winginx\php56\php.exe
set PROCESSOR=d:\projects\preprocessor\build\preprocessor.php

::  **********************************************************************
::   so let's go!
%PHPBIN% -q  %PROCESSOR% /Ddst=build.RNS_Inno /Dtarget=release configRNS_Inno.xml
pause
