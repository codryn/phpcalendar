#!/bin/bash
set -e

echo "Running Calendar Mapping examples..."
php examples/calendar-mapping.php > /dev/null || { echo "ERROR: calendar-mapping.php failed"; exit 1; }

echo "✓ All example scripts executed successfully"
