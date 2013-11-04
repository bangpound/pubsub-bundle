<?php

namespace Bangpound\Bundle\PubsubBundle\CouchDocument;

use Bangpound\Atom\DataBundle\CouchDocument\FeedType;

/**
 * Class AtomFeed
 * @package Bangpound\Bundle\PubsubBundle\CouchDocument
 */
class AtomFeed extends FeedType
{
    /**
     * @var AtomEntry (atom:entryType)
     * @internal element (http://www.w3.org/2001/XMLSchema)
     */
    protected $entries;
}
