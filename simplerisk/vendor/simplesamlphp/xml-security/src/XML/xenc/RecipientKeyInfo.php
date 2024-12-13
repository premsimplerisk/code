<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSecurity\XML\xenc;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSecurity\Constants as C;
use SimpleSAML\XMLSecurity\XML\ds\AbstractKeyInfoType;
use SimpleSAML\XMLSecurity\XML\ds\KeyName;
use SimpleSAML\XMLSecurity\XML\ds\KeyValue;
use SimpleSAML\XMLSecurity\XML\ds\RetrievalMethod;
use SimpleSAML\XMLSecurity\XML\ds\X509Data;

use function array_merge;

/**
 * Class representing a xenc:RecipientKeyInfo element.
 *
 * @package simplesamlphp/xml-security
 */
final class RecipientKeyInfo extends AbstractKeyInfoType
{
    /** @var string */
    public const NS = C::NS_XENC;

    /** @var string */
    public const NS_PREFIX = 'xenc';


    /**
     * Convert XML into a RecipientKeyInfo
     *
     * @param \DOMElement $xml The XML element we should load
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, 'RecipientKeyInfo', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, RecipientKeyInfo::NS, InvalidDOMElementException::class);

        $Id = self::getOptionalAttribute($xml, 'Id', null);

        $keyName = KeyName::getChildrenOfClass($xml);
        $keyValue = KeyValue::getChildrenOfClass($xml);
        $retrievalMethod = RetrievalMethod::getChildrenOfClass($xml);
        $x509Data = X509Data::getChildrenOfClass($xml);
        //$pgpData = PGPData::getChildrenOfClass($xml);
        //$spkiData = SPKIData::getChildrenOfClass($xml);
        //$mgmtData = MgmtData::getChildrenOfClass($xml);
        $other = self::getChildElementsFromXML($xml);

        $info = array_merge(
            $keyName,
            $keyValue,
            $retrievalMethod,
            $x509Data,
            //$pgpdata,
            //$spkidata,
            //$mgmtdata,
            $other,
        );

        return new static($info, $Id);
    }
}
