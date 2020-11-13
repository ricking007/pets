<?php
namespace App\language;

interface Language{    
    public function getLanguage();
    public function TxCampoObrigatorio();
    public function TxCPFInvalido();
    public function TxEmailInvalido();
    public function TxDataInvalida();
    public function TxTamanhoMaximo();
    public function TxTipoDeArquivoInvalido();
    public function TxConfirmeQueVoceNaoEUmRobo();
    public function TxOcorremErrosDeValidacao();
}