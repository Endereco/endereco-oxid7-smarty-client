#!/bin/bash

# Versions to be installed with their corresponding Composer versions
declare -A versions=( ["6.1"]="1" ["6.2"]="1" ["6.3"]="1" ["6.4"]="2" ["6.5"]="2" )


# Loop through each version and set up the projects
for version in "${!versions[@]}"
do
    # Determine which Composer version to use based on the version
    composer_version="${versions[$version]}"

    # Target directory for each project
    TARGET_DIR="shops/$version"

    # Remove the existing directory and recreate it
    rm -rf "$TARGET_DIR"
    mkdir -p "$TARGET_DIR"

    # Use Docker to run Composer 2 for each project version
    docker run --rm \
        -v $(pwd):/app \
        -w /app \
        composer:$composer_version \
        composer create-project --no-dev --ignore-platform-reqs oxid-esales/oxideshop-project $TARGET_DIR dev-b-$version-ce
done

# Change the owner of the shops directory to the user who ran the script
sudo chown -R $(whoami) shops/
