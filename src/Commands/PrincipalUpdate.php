<?php

namespace AdobeConnectClient\Commands;

use AdobeConnectClient\Abstracts\Command;
use AdobeConnectClient\Contracts\ArrayableInterface;
use AdobeConnectClient\Converter\Converter;
use AdobeConnectClient\Helpers\StatusValidate;

/**
 * Update a Principal.
 *
 * More info see {@link https://helpx.adobe.com/adobe-connect/webservices/principal-update.html}
 */
class PrincipalUpdate extends Command
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param ArrayableInterface $principal
     */
    public function __construct(ArrayableInterface $principal)
    {
        $this->parameters = [
            'action' => 'principal-update',
        ];

        $this->parameters += $principal->toArray();
    }

    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    protected function process()
    {
        foreach (['password', 'type', 'has-children'] as $prohibited) {
            if (isset($this->parameters[$prohibited])) {
                unset($this->parameters[$prohibited]);
            }
        }

        $response = Converter::convert(
            $this->client->doGet(
                $this->parameters + ['session' => $this->client->getSession()]
            )
        );
        StatusValidate::validate($response['status']);

        return true;
    }
}
