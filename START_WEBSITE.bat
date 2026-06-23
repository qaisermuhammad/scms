@echo off
setlocal

cd /d "%~dp0scms_website"

if exist ".venv\Scripts\python.exe" (
    ".venv\Scripts\python.exe" --version >nul 2>nul
    if errorlevel 1 (
        echo Rebuilding local website environment...
        rmdir /s /q ".venv"
    )
)

if not exist ".venv\Scripts\python.exe" (
    echo Setting up the website for the first time...
    python -m venv .venv
    if errorlevel 1 (
        echo.
        echo Python is not installed or not available.
        echo Please install Python from https://www.python.org/downloads/
        echo Make sure to tick "Add python.exe to PATH" during installation.
        pause
        exit /b 1
    )
)

echo Installing/checking required packages...
".venv\Scripts\python.exe" -m pip install -r requirements.txt
if errorlevel 1 (
    echo.
    echo Package installation failed. Please check your internet connection.
    pause
    exit /b 1
)

echo.
echo Starting Safi College of Medical Sciences website...
echo On this computer, open: http://127.0.0.1:5000
echo Keep this window open while using the website.
echo Press CTRL+C in this window to stop it.
echo.

start "" "http://127.0.0.1:5000"
cmd /k ".venv\Scripts\python.exe app.py"

pause
