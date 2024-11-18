<?php

/**
 * Clase cliente del Api2 de Flow
 * @Filename: FlowApi.class.php
 * @version: 2.1
 * @Author: flow.cl
 * @Email: csepulveda@tuxpan.com
 * @Date: 28-04-2017 11:32
 * @Last Modified by: Carlos Sepulveda
 * @Last Modified time: 01-07-2021 
 */


class FlowApiV2
{
    private $endpoint;
    private $apiKey;
    private $secretKey;

    public function __construct($endpoint, $apiKey, $secretKey = "")
    {
        $this->endpoint = $endpoint;
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function order($data)
    {
        $endpoint = $this->getEndpoint("order/create");
        return $this->processResponse($this->httpRequest($endpoint, "POST", $data));
    }

    public function getOrderStatus($token)
    {
        $endpoint = $this->getEndpoint("order/token/".$token);
        return $this->processResponse($this->httpRequest($endpoint, "GET"));
    }

    private function getHeaders()
    {
        return array(
            "Authorization: Basic ".$this->getAuthHeader(),
            "Content-type: application/json"
        );
    }

    private function getAuthHeader()
    {
        return base64_encode($this->apiKey.":".$this->secretKey);
    }
    
    private function getEndpoint($service)
    {
        return $this->endpoint."/".$service;
    }

    private function processResponse($response)
    {
        $output = json_decode($response["output"],TRUE);
        $info =  $response["info"];
        $http_code = $info["http_code"];
        if($response["error"] != null){
            $http_code = 443;
        }
        $messageError = $this->getFlowErrorMessage($output,$http_code);
        if($messageError != null ){
            throw new Exception($messageError,$http_code);
        }
        return $output;
    }

    

    private function getFlowErrorMessage($flowError , $http_code)
    {
        $message = null;
        switch ($http_code)
        {
            case 401:
            {
                $message = "El API-Key es inv&aacute;lido, verifique sus credenciales en la configuraci&oacute;n de su cuenta de Flow. Para m&aacute;s informaci&oacute;n visite <a href='https://ayuda.flow.cl/'>https://ayuda.flow.cl/</a> y revise la secci&oacute;n de ayuda t&eacute;cnica.";
                break;
            }
            case 404:
            {
                $message = "El servicio no est&aacute; disponible. Para m&aacute;s informaci&oacute;n comun&iacute;quese con <a href='mailto:soporte@flow.cl'>soporte@flow.cl</a>";
                break;
            }
            case 443;
            {
                $message = "Ha ocurrido un error de comunicación con Flow. Para más información visite <a href='https://ayuda.flow.cl/'>https://ayuda.flow.cl/</a> y revise la sección de ayuda t&eacute;cnica." ;
                break;
            } 
        }
        
        $flow_code = $flowError["code"];
        $codesErrosFields = array(103,104);
        if(in_array($flow_code, $codesErrosFields)) {
            $businessErrors = $flowError["errors"][0];
            $subMessage = $businessErrors["message"];
            switch ($businessErrors["field"]) {
                case "currency":
                case "amount": {
                    $visite = "Para m&aacute;s informaci&oacute;n visite <a href='https://ayuda.flow.cl/'>https://ayuda.flow.cl/</a> y revise la secci&oacute;n de ayuda t&eacute;cnica.";
                    break;
                }
                case "email":
                default: {
                    $visite = "Para m&aacute;s informaci&oacute;n comun&iacute;quese con <a href='mailto:soporte@flow.cl'>soporte@flow.cl</a>";
                    break;
                }
            }            
            $message = "Ha ocurrido un error in&eacute;sperado: $subMessage.<br>$visite";
        }
        return $message ;
    }

    /**
     * Funcion que hace el llamado via http POST
     * @param string $url url a invocar
     * @param array $params los datos a enviar
     * @return array el resultado de la llamada
     * @throws Exception
     */
    private function httpRequest($url, $type, $params = array())
    {
        $ch = curl_init();

        if ($type === "POST") {
            $params = json_encode($params);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
        $output = curl_exec($ch);
        
        $error = null;
        $info = curl_getinfo($ch);
        
        if ($output === FALSE) {
            $error = curl_error($ch);
        }

        curl_close($ch);

        return array(
            "output" => $output, 
            "info" => $info,
            "error" => $error
        );
    }
}