<?php
declare(strict_types=1);

namespace ScriptFUSION\Steam250\Import;

use ScriptFUSION\Mapper\AnonymousMapping;
use ScriptFUSION\Mapper\DataType;
use ScriptFUSION\Mapper\Strategy\Callback;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSION\Mapper\Strategy\Type;
use ScriptFUSION\Porter\Provider\Steam\Resource\ScrapeAppDetails;
use ScriptFUSION\Porter\Specification\ImportSpecification;
use ScriptFUSION\Porter\Transform\Mapping\MappingTransformer;
use ScriptFUSION\Top250\Shared\Platform;

class AppSpecification extends ImportSpecification
{
    public function __construct(int $appId)
    {
        parent::__construct(new ScrapeAppDetails($appId));

        $this->addTransformer(
            new MappingTransformer(
                new AnonymousMapping([
                    'name' => new Copy('name'),
                    'type' => new Copy('type'),
                    'release_date' => new Callback(
                        function (array $data): ?int {
                            return $data['release_date'] ? $data['release_date']->getTimestamp() : null;
                        }
                    ),
                    'tags' => new Copy('tags'),
                    'price' => new Copy('price'),
                    'discount_price' => new Copy('discount_price'),
                    'discount' => new Copy('discount'),
                    'vrx' => new Type(DataType::INTEGER(), new Copy('vrx')),
                    'positive_reviews' => new Copy('positive_reviews'),
                    'negative_reviews' => new Copy('negative_reviews'),
                    'total_reviews' => new Callback(
                        function (array $data): int {
                            return $data['positive_reviews'] + $data['negative_reviews'];
                        }
                    ),
                    'platforms' => new Callback(
                        function (array $data): int {
                            $platforms = 0;
                            $data['windows'] && $platforms |= Platform::WINDOWS;
                            $data['linux'] && $platforms |= Platform::LINUX;
                            $data['mac'] && $platforms |= Platform::MAC;
                            $data['vive'] && $platforms |= Platform::VIVE;
                            $data['occulus'] && $platforms |= Platform::OCCULUS;
                            $data['wmr'] && $platforms |= Platform::WMR;

                            return $platforms;
                        }
                    ),
                ])
            )
        );
    }
}
