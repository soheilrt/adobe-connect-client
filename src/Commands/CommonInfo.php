<?php

namespace AdobeConnectClient\Commands;

use AdobeConnectClient\Abstracts\Command;
use AdobeConnectClient\Converter\Converter;
use AdobeConnectClient\Entities\CommonInfo as CommonInfoEntity;
use AdobeConnectClient\Helpers\SetEntityAttributes as FillObject;
use AdobeConnectClient\Helpers\StatusValidate;
use AdobeConnectClient\Helpers\ValueTransform as VT;

/**
 * Gets the common info.
 *
 * More info see {@link https://helpx.adobe.com/adobe-connect/webservices/common-info.html#common_info}
 */
class CommonInfo extends Command
{
    /**
     * @var string
     */
    protected $domain = '';

    /**
     * @param string $domain
     */
    public function __construct($domain = '')
    {
        $this->domain = $domain;
    }

    /**
     * {@inheritdoc}
     *
     * @return CommonInfoEntity
     */
    protected function process()
    {
        $parameters = [
            'action' => 'common-info',
        ];

        if (!empty($this->domain)) {
            $parameters += [
                'domain' => VT::toString($this->domain),
            ];
        }

        $response = Converter::convert(
            $this->client->doGet($parameters)
        );
        StatusValidate::validate($response['status']);
        $commonInfo = new CommonInfoEntity();
        FillObject::setAttributes($commonInfo, $response['common']);

        return $commonInfo;
    }
}
