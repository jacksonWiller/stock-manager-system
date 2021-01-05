#!/bin/bash

# if [ "$2" == "-db" ]
# then
# echo "rebuilding database ..."
# php bin/console doctrine:schema:drop -n -q --force --full-database
# rm src/Migrations/*.php
# php bin/console make:migration
# php bin/console doctrine:migrations:migrate -n -q
# php bin/console doctrine:fixtures:load -n -q
# fi

# if [ -n "$1" ]
# then
# ./bin/phpunit $1
# else
# ./bin/phpunit
# fi


 /**
     * @Route(name="product_list", methods={"GET"}, path="/api/product")
     */
    public function listarProducts(Request $request, PersonManager $manager)
    {
        try {
            $this->isGrantedAcl('uloc/user/list');

            list($page, $limit, $offset) = $this->getPagination($request, 100, self::MAX_RESULT_LIMIT);

            $products = $manager->list($limit, $offset, ['role' => 'ROLE_ERP']);
            $products['result'] = $this->serialize($products['result'], 'array', 'public', function (ApiRepresentationMetadata $metadata) {
                Product::loadApiRepresentation($metadata);
            });
            return $this->createApiResponseEncodeArray($products, 200);
        } catch (\Exception $e) {
            return $this->errorHandler->handlerError($e->getMessage());
        }
    }

