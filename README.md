# SakuraFrp Download Test

## What is this

This repository contains a few handy scripts that was used by SakuraFrp maintainers when publishing new version.

## Usage

1. Cleanup downloads.json:

    ```bash
    php clear.php
    ```

2. Export hash list:

    ```bash
    php export.php [Dir]
    ```

3. Modify `download_test.php`, ensure `FRPC_VERSION`、`LAUNCHER_VERSION` has the latest value

4. Perform download test:

    ```bash
    php download_test.php
    ```

5. Update `downloads.json`
