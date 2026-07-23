@echo off
:loop
cd /d d:\xampp\htdocs\erm_dev\erm_karsa\erm_karsa
echo [%date% %time%] Memproses antrean...
"D:\xampp\php84\php.exe" artisan wa:process-queue
timeout /t 60 /nobreak
goto loop