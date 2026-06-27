<#
.SYNOPSIS
    A script to stage, commit, and push changes to a Git repository.
.DESCRIPTION
    This script automates the process of adding all modified files,
    committing them with a user-provided message, and pushing to a specified remote and branch.
.PARAMETER CommitMessage
    The commit message to use. If not provided, the script will prompt for one.
.PARAMETER RemoteName
    The name of the remote to push to. Defaults to 'admintariq'.
.PARAMETER BranchName
    The name of the branch to push to. Defaults to 'main'.
.EXAMPLE
    .\push-otp-config.ps1 -CommitMessage "Your detailed commit message here"
#>
param (
    [Parameter(Mandatory=$false)]
    [string]$CommitMessage,
    [string]$RemoteName = 'admintariq',
    [string]$BranchName = 'main'
)

$ErrorActionPreference = 'Stop'

try {
    Set-Location 'c:\xampp\htdocs\tariq'

    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "   Pushing Changes to GitHub" -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan

    # If no commit message is passed as an argument, prompt the user for one.
    if ([string]::IsNullOrWhiteSpace($CommitMessage)) {
        $CommitMessage = Read-Host "Enter your commit message"
    }

    Write-Host "`n1. Adding all modified files..." -ForegroundColor Yellow
    git add .
    Write-Host '   ✓ Files added' -ForegroundColor Green

    Write-Host "`n2. Creating commit..." -ForegroundColor Yellow
    git commit -m $CommitMessage
    Write-Host "   ✓ Commit created with message: '$CommitMessage'" -ForegroundColor Green

    Write-Host "`n3. Pushing to '$RemoteName' remote on branch '$BranchName'..." -ForegroundColor Yellow
    git push $RemoteName $BranchName
    Write-Host '   ✓ Pushed successfully!' -ForegroundColor Green

    Write-Host "`n========================================" -ForegroundColor Cyan
    Write-Host '   All changes pushed to GitHub!' -ForegroundColor Cyan
    Write-Host "========================================" -ForegroundColor Cyan
}
catch {
    Write-Host "`nError occurred during the git process:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}
