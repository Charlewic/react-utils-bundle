# react-utils-bundle
React commands for deploying &amp; debugging app into Symfony environment.  
This bundle is compatible Symfony 3.X versions (perhaps its fine with 4.X, but not tested).

## Prerequisites

You need to install npm and yarn (optionnal) before use this bundle's commands.

- Npm : [https://docs.npmjs.com/getting-started/installing-node]()
- Yarn : [https://yarnpkg.com/lang/en/docs/install/]() 

## Configure

This bundle works with npm or yarn shells.  
You can define the path of this in config.yml file :

````
so_react_utils:
    npm_bin_path: /usr/bin/npm # default value
    yarn_bin_path: /usr/bin/yarn # default value
````

## How to use

This bundle provide 3 commands :

- soreact_utils:npm:install : install react dependencies with npm
- soreact_utils:yarn:install : install react dependencies with yarn
- soreact_utils:npm:run : run webpack script

````
bin/console soreact_utils:npm:install @BundleName
# Argument1 : @BundleName : Name of the bundle who have the React Components to install *required*
# option1 : --timeout or -t : number of seconds before the process raise a timeout *optionnal* (default 300)
# option2 : --reactFolderPath or -p : path to react folder in the bundle *optionnal* (default ReactComponent)

bin/console soreact_utils:yarn:install @BundleName --timeout 1
# Argument1 : @BundleName : Name of the bundle who have the React Components to install *required*
# option1 : --timeout or -t : number of seconds before the process raise a timeout *optionnal* (default 300)
# option2 : --reactFolderPath or -p : path to react folder in the bundle *optionnal* (default ReactComponent)

bin/console soreact_utils:npm:run @BundleName webpackScript
# Argument1 : @BundleName : Name of the bundle who have the React Components to install *required*
# Argument1 : webpackScript : Name of the webpack script to run *required*
# option1 : --reactFolderPath or -p : path to react folder in the bundle *optionnal* (default ReactComponent)
````
