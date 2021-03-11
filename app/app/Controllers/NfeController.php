<?php

namespace App\Controllers;

use Core\Controllers\Controller;
use App\Models\Nfe;
use Core\Session;
use Core\Helpers\File;


class NfeController extends Controller
{
    private $session;
    private $file;

    public function __construct()
    {
        $this->session = new Session();
        $this->file = new File;
    }

    public function index()
    {
        $nfe = new Nfe();

        $result = $nfe->limit(10)->get();

        $this->display('nfe/index', ['result' => $result]);
    }

    public function record()
    {
        $this->display("nfe/record");
    }

    public function recordReview()
    {
        $message = '';
        $alert_type = '';
        $data = [];
        if (!$this->validType($_FILES['file']['type'])) {
            $message = 'Erro: Tipo de arquivo inválido. Por favor, importe um arquivo XML';
            $alert_type = 'danger';
        } else {
            if (isset($_FILES['file']) && ($_FILES['file']['error'] == UPLOAD_ERR_OK)) {
                $xml = simplexml_load_file($_FILES['file']['tmp_name']);
                if (isset($xml->NFe->infNFe->emit)) {
                    if (!$this->validCNPJ($xml->NFe->infNFe->emit->CNPJ)) {
                        $message = 'Erro: O CNPJ não está autorizado a ser importado';
                        $alert_type = 'danger';
                    } else {
                        if (!$this->validProtocoloAutorizacao($xml->protNFe->infProt->nProt)) {
                            $message = 'Erro: Não Possui Protocolo de autorização';
                            $alert_type = 'danger';
                        } else {
                            $filename = $this->file->move($_FILES, 'storage/nfe/');
                            $this->session->set('lastnfe', $filename);
                            $data['cnpj'] = $xml->NFe->infNFe->emit->CNPJ;
                            $data['numero'] = $xml->NFe->infNFe->ide->nNF;
                            $data['data'] = $xml->NFe->infNFe->ide->dhEmi;
                            $data['dest'] = (array) $xml->NFe->infNFe->dest;
                            $data['valor'] = $xml->NFe->infNFe->total->ICMSTot->vNF;
                        }
                    }
                } else {
                    $message = 'Erro: NFe inválido';
                    $alert_type = 'danger';
                }
            }
        }

        $data['message'] =  $message;
        $data['alert_type'] =  $alert_type;

        $this->display("nfe/recordReview", $data);
    }

    private function validType($type)
    {
        return $type == 'text/xml';
    }

    private function validCNPJ($cnpj)
    {
        return $cnpj == '03616814000305';
    }

    private function validProtocoloAutorizacao($protocol)
    {
        return !empty($protocol);
    }

    public function save()
    {
        $xml = simplexml_load_file($this->session->get('lastnfe'));
        $nfe = new Nfe();

        $data['filename'] = $this->session->get('lastnfe');
        $data['cnpj'] = $xml->NFe->infNFe->emit->CNPJ;
        $data['name'] = $xml->NFe->infNFe->emit->xNome;
        $data['protocol'] = $xml->protNFe->infProt->nProt;
        $data['number'] = $xml->NFe->infNFe->ide->nNF;
        $data['date'] = $xml->NFe->infNFe->ide->dhEmi;
        $data['value'] = $xml->NFe->infNFe->total->ICMSTot->vNF;
        if ($nfe->insert($data)) {
            $data['message'] =  "Importado com sucesso";
            $data['alert_type'] =  'primary';
        } else {
            $data['message'] =  'Erro: Não foi possível registrar NFe no banco de dados. Por favor, Tente mais tarde.';
            $data['alert_type'] =  'danger';
        }
        $this->display("nfe/saved", $data);
    }
}
