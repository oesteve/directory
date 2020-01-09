#!/bin/sh
echo "Staring e2e environment"
docker-compose up --build -d

echo "Running tests"
#docker run -it -v $PWD:/e2e -w /e2e --network=host cypress/included:3.2.0
npm run cypress
EXIT_CODE=$?

echo "Stoping e2e environment"
docker-compose -f docker-compose.yml down

exit $EXIT_CODE