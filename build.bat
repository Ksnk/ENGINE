@echo off
set PHPBIN=d:\Winginx\php72\php.exe
set PROCESSOR=d:\projects\preprocessor\build\preprocessor.php

::  **********************************************************************
::   so let's go!
%PHPBIN% -q  %PROCESSOR% /Ddst=build.lapsiTV /Dtarget=release configLapsiTV.xml
