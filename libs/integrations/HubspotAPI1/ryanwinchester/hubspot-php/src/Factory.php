<?php

namespace MasterPopups\SevenShores\Hubspot;

use MasterPopups\SevenShores\Hubspot\Http\Client;

/**
 * Class Factory
 *
 * @method \MasterPopups\SevenShores\Hubspot\Resources\BlogAuthors blogAuthors()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Blogs blogs()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\BlogPosts blogPosts()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\BlogTopics blogTopics()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Companies companies()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\CompanyProperties companyProperties()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\ContactLists contactLists()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\ContactProperties contactProperties()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Contacts contacts()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Email email()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\EmailEvents emailEvents()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Engagements engagements()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Files files()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Forms forms()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Keywords keywords()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Pages pages()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\SocialMedia socialMedia()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Timeline timeline()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Workflows workflows()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Events events()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\DealPipelines dealPipelines()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\DealProperties dealProperties()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Deals deals()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\Owners owners()
 * @method \MasterPopups\SevenShores\Hubspot\Resources\SingleEmail singleEmail()
 */
class Factory
{
    /**
     * C O N S T R U C T O R ( ^_^)y
     *
     * @param  array $config An array of configurations. You need at least the 'key'.
     * @param  Client $client
     * @param array $clientOptions options to be send with each request
     * @param bool $wrapResponse wrap request response in own Response object
     */
    public function __construct($config = [], $client = null, $clientOptions = [], $wrapResponse = true)
    {
        $this->client = $client ?: new Client($config, null, $clientOptions, $wrapResponse);
    }

    /**
     * Create an instance of the service with an API key.
     *
     * @param  string $api_key Hubspot API key.
     * @param  Client $client An Http client.
     * @param array $clientOptions options to be send with each request
     * @param bool $wrapResponse wrap request response in own Response object
     * @return static
     */
    public static function create($api_key = null, $client = null, $clientOptions = [], $wrapResponse = true)
    {
        return new static(['key' => $api_key], $client, $clientOptions, $wrapResponse);
    }

    /**
     * Create an instance of the service with an Oauth token.
     *
     * @param  string $token Hubspot oauth access token.
     * @param  Client $client An Http client.
     * @param array $clientOptions options to be send with each request
     * @param bool $wrapResponse wrap request response in own Response object
     * @return static
     */
    public static function createWithToken($token, $client = null, $clientOptions = [], $wrapResponse = true)
    {
        return new static(['key' => $token, 'oauth' => true], $client, $clientOptions, $wrapResponse);
    }

    /**
     * Return an instance of a Resource based on the method called.
     *
     * @param  string  $name
     * @param  array   $arguments
     * @return \MasterPopups\SevenShores\Hubspot\Resources\Resource
     */
    function __call($name, $arguments = null)
    {
        $resource = 'MasterPopups\\SevenShores\\Hubspot\\Resources\\' . ucfirst($name);

        return new $resource($this->client);
    }
}