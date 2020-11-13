<?php
namespace App\language;

class ptBR implements Language{  
    public function getLanguage(){
        $lang = explode("\\",get_class());
        return end($lang);
    }
    public function TxCPFInvalido() {
        return "CPF inválido";
    }

    public function TxCampoObrigatorio() {
        return "Campo obrigatório";
    }

    public function TxConfirmeQueVoceNaoEUmRobo() {
        return "Confirme que você não é um robô";
    }

    public function TxDataInvalida() {
        return "Data inválida";
    }

    public function TxEmailInvalido() {
        return "Email inválido";
    }

    public function TxOcorremErrosDeValidacao() {
        return "Ocorreram erros de validação";
    }

    public function TxTamanhoMaximo() {
        return "Tamanho máximo";
    }

    public function TxTipoDeArquivoInvalido() {
        return "Tipo de arquivo inválido";
    }

  
}