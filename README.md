# API Data Fetcher

This project was developed using **DDEV** to build the local environment for plugin development and testing.  
To assess the plugin, you can either:  
1. Download the zip file located on the root path and install it on your WordPress environment.
2. Build the DDEV environment provided in this repository.

---

## Requirements

This project requires the following dependencies:

- [DDEV](https://ddev.readthedocs.io/en/stable/)
- [Node.js](https://nodejs.org/)


### Install Global Dependencies:
Ensure DDEV is installed on your machine.

If you are a Windows user, it is highly recommended to use WSL2 for an optimal development experience. Please follow this [Windows WSL2 DDEV installation guide](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/#wsl2-docker-ce-inside-install-script).

For any other OS, the installation is straightforward. Follow the [DDEV installation guide](https://ddev.readthedocs.io/en/stable/).

### Clone the Repository:
```bash
git clone https://github.com/Repository/WordPressProject.git
```

### Setup DDEV for WordPress:
1. Navigate to the project directory where the *.ddev* directory is located.
   ```bash
   cd /path/to/your/project
   ```

2. Import the database:
   You should have a dump of the project database (e.g., database.sql). To import it:
   1. Place the database dump file in the root of your project (where *.ddev* directory is located).
   2. Run the following command to import the dump file:
   ```bash
   ddev import-db --file=database.sql
   ```

3. Start DDEV:
   ```bash
   ddev start
   ```

Your local development environment is now ready. Access it via `https://api-data-fetcher.ddev.site`.

---

## The Plugin

This plugin was built using Gulp.js for the Frontend. If you're going to work on the frontend, follow these steps:

1. Go to the folder: `/wp-content/plugins/api-data-fetcher/gulp/`
2. Ensure you are using **Node.js version **21.x**. Use [nvm](https://github.com/nvm-sh/nvm) (Node Version Manager) to switch to the correct Node.js version by running: 
   ```bash
   nvm use 21
   ```
3. Install the necessary packages by running: `npm install` 
4. Start the watcher to track file changes by running: `gulp` command

---

