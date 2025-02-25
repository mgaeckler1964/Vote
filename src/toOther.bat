set var=%cd%
echo %var%

if "%var%"=="C:\CRESD\object" goto nix

if "%var%" GTR "E" goto netz

set source=C:\CRESD\Source\Internet\Vote\src
set target=M:\wwwroot\Vote

goto end

:netz
set source=M:\wwwroot\Vote
set target=C:\CRESD\Source\Internet\Vote\src

:end

mirror -l %source% %target%

:nix

pause