<?php namespace MasterPopups\Includes\ServiceIntegration;

use MasterPopups\SevenShores\Hubspot\Factory;

class HubspotIntegration extends ServiceIntegration {
    protected $api_url = 'https://api.hubapi.com/';

    /*
    |---------------------------------------------------------------------------------------------------
    | Constructor
    |---------------------------------------------------------------------------------------------------
    */
    public function __construct( $api_key = '' ){
        $this->api_key = $api_key;
        $this->service = Factory::create( $this->api_key );
    }

    /*
    |---------------------------------------------------------------------------------------------------
    | Comprueba si la conexión con el servicio es exitosa
    |---------------------------------------------------------------------------------------------------
    */
    public function is_connect(){
        if( ! $this->service ){
            return false;
        }
        try{
            $response = $this->service->contactLists()->getAllStatic( [ 'count' => 1 ] );
            return true;
        } catch( \Exception $e ){
            $this->error = $e->getMessage();
            return false;
        }
    }

    /*
    |---------------------------------------------------------------------------------------------------
    | Retorna todas las listas
    |---------------------------------------------------------------------------------------------------
    */
    public function get_lists(){
        try{
            $items = array();
            $lists = $this->service->contactLists()->getAllStatic( [ 'count' => 200 ] )->data->lists;
            if( $lists ){
                foreach( $lists as $list ){
                    $items[$list->listId] = $list->name;
                }
            }
            return $items;
        } catch( \Exception $e ){
            $this->error = $e->getMessage();
            return array();
        }
    }

    /*
    |---------------------------------------------------------------------------------------------------
    | Agrega un suscriptor a una lista
    |---------------------------------------------------------------------------------------------------
    */
    public function add_subscriber( $email, $data = array() ){
        $first_name = $data['first_name'];
        $first_name['value'] = ! empty( $first_name['value'] ) ? $first_name['value'] : '';
        $first_name['name'] = ! empty( $first_name['name'] ) ? $first_name['name'] : 'firstname';

        $last_name = $data['last_name'];
        $last_name['value'] = ! empty( $last_name['value'] ) ? $last_name['value'] : '';
        $last_name['name'] = ! empty( $last_name['name'] ) ? $last_name['name'] : 'lastname';

        $contact = null;
        try{
            $contact = $this->service->contacts()->getByEmail( $email );
        } catch( \Exception $e ){
            $this->error = $e->getMessage();
            $contact = null;
        }

        try{
            //Si el suscriptor no existe entonces lo creamos
            if( $contact == null ){
                //Datos necesarios para la suscripción
                $params = array();
                $params[] = array(
                    'property' => 'email',
                    'value' => $email
                );
                $params[] = array(
                    'property' => $first_name['name'],
                    'value' => $first_name['value'],
                );
                $params[] = array(
                    'property' => $last_name['name'],
                    'value' => $last_name['value']
                );

                if( ! empty( $data['custom_fields'] ) ){
                    $custom_fields = $this->get_custom_fields();
                    foreach( $custom_fields as $cf_id => $cf_name ){
                        if( isset( $data['custom_fields'][$cf_name] ) ){
                            $params[] = array(
                                'property' => $cf_name,
                                'value' => $data['custom_fields'][$cf_name]
                            );
                        }
                    }
                }
                //Suscribir nuevo usuario
                $contact = $this->service->contacts()->create( $params );
            }

            $exist = false;
            $subscriber_id = $contact->data->vid;
            $subscribers = $this->service->contactLists()->contacts( $this->list_id )->getData()->contacts;
            foreach( $subscribers as $subscriber ){
                if( $subscriber->vid == $subscriber_id ){
                    $exist = true;
                    break;
                }
            }
            if( ! $exist ){
                $this->response = $this->service->contactLists()->addContact( $this->list_id, array( $subscriber_id ) );
                return true;
            } else{
                $this->error = $this->messages['subscriber_exists'];
                return false;
            }
        } catch( \Exception $e ){
            $this->error = $e->getMessage();
            return false;
        }
        return false;
    }

    /*
    |---------------------------------------------------------------------------------------------------
    | Retorna todos los campos por defecto
    |---------------------------------------------------------------------------------------------------
    */
    public function get_default_fields(){
        return array(
            'firstname',
            'lastname',
        );
    }

    /*
    |---------------------------------------------------------------------------------------------------
    | Retorna todos los campos personalizados
    |---------------------------------------------------------------------------------------------------
    */
    public function get_custom_fields(){
        $items = array();
        $url = $this->api_url . '/properties/v1/contacts/properties?hapikey=' . $this->api_key;
        $response = wp_remote_get( $url );
        if( is_array( $response ) && ! is_wp_error( $response ) ){
            $fields = json_decode( $response['body'] );
            foreach( $fields as $field ){
                $items[] = $field->name;
            }
        } else{
            return array();
        }
        return $items;
    }


}

