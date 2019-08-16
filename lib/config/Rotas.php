<?php

/*
 * Classe para aplicar rotas para classe - alias.
 */
class Rotas {

    private $rotas = [];

    private $rota;
    private $rotaClasse;
    private $rotaMetodo;

    public function __construct(string $controller, string $action){
        
    	$this->setRotaAdiantamento();
        $this->setRotaAtendimento();
        $this->setRotaAtendimentoSenha();
        $this->setRotaCliente();
        $this->setRotaComissaoVenda();
        $this->setRotaRelatorioProducao();
        $this->setRotaContabil();
        $this->setRotaDesenvolvedor();
        $this->setRotaFaturamentoDemanda();
        $this->setRotaGuiche();
        $this->setRotaLogin();
        $this->setRotaMonitorChamada();
        $this->setRotaOp();
        $this->setRotaPacote();
        $this->setRotaPedido();
        $this->setRotaPedidoVenda();
        $this->setRotaPendenciaVenda();
        $this->setRotaPermissao();
        $this->setRotaProducao();
        $this->setRotaServicoGrupo();
        $this->setRotaServico();
        $this->setRotaServicoPreco();
        $this->setRotaProdutoServicoIncluso();
        $this->setRotaSetor();
        $this->setRotaTesteAsind();
        $this->setRotaTriagem();
		$this->setRotaUnidade();
        $this->setRotaUsuario();
		$this->setRotaWsSync();

		$this->rotas['AsindTeste/jsonSetor'] = 'asind-teste/jsonSetor';
		$this->rotas['AsindTeste/jsonSituacao'] = 'asind-teste/jsonSituacao';

        $this->setRota($controller, $action);
    }

    private function setRota(string $controller, string $action){
    	$_matchRota = array_search($controller."/".$action, $this->rotas);
    	$matchRota = $_matchRota ? $_matchRota : array_search($controller, $this->rotas);
    	$this->rota =  $matchRota ? $matchRota : $controller."/".$action;

    	$rota = explode("/", $this->rota);
    	$this->rotaClasse = $rota[0];
    	$this->rotaMetodo = $rota[1];
    }

    public function getRota(){
    	return $this->rota;
    }

    public function getRotaClass(){
    	return $this->rotaClasse;
    }

    public function getRotaMetodo(){
    	return $this->rotaMetodo;
    }




    private function setRotaAdiantamento(){
    	$this->rotas['AdiantamentoController/initial'] = 'adiantamento';
    }

    private function setRotaAtendimento(){
    	$this->rotas['AtendimentoBase/jsPegarPedido'] = 'atendimento/jsPegarPedido';

    	$this->rotas['AtendimentoArteController/initial'] = 'atendimento-arte';
    	$this->rotas['AtendimentoArteController/editar'] = 'atendimento-arte/editar';
    	$this->rotas['AtendimentoArteController/editarPedido'] = 'atendimento-arte/editarPedido';
    	$this->rotas['AtendimentoArteController/editarPelaTabela'] = 'atendimento-arte/editar-pela-tabela';
    	$this->rotas['AtendimentoArteController/chamar'] = 'atendimento-arte/chamar';
    	$this->rotas['AtendimentoArteController/iniciar'] = 'atendimento-arte/iniciar';
    	$this->rotas['AtendimentoArteController/concluir'] = 'atendimento-arte/concluir';
    	$this->rotas['AtendimentoArteController/excluir'] = 'atendimento-arte/excluir';
    	$this->rotas['AtendimentoArteController/anexar'] = 'atendimento-arte/anexar';
    	$this->rotas['AtendimentoArteController/removerAnexo'] = 'atendimento-arte/removerAnexo';
    	$this->rotas['AtendimentoArteController/adicionarPedido'] = 'atendimento-arte/adicionarPedido';
    	$this->rotas['AtendimentoArteController/removerPedido'] = 'atendimento-arte/removerPedido';

    	$this->rotas['AtendimentoBordadoController/initial'] = 'atendimento-bordado';
    	$this->rotas['AtendimentoBordadoController/editar'] = 'atendimento-bordado/editar';
    	$this->rotas['AtendimentoBordadoController/editarPedido'] = 'atendimento-bordado/editarPedido';
    	$this->rotas['AtendimentoBordadoController/editarPelaTabela'] = 'atendimento-bordado/editar-pela-tabela';
    	$this->rotas['AtendimentoBordadoController/chamar'] = 'atendimento-bordado/chamar';
    	$this->rotas['AtendimentoBordadoController/iniciar'] = 'atendimento-bordado/iniciar';
    	$this->rotas['AtendimentoBordadoController/concluir'] = 'atendimento-bordado/concluir';
    	$this->rotas['AtendimentoBordadoController/excluir'] = 'atendimento-bordado/excluir';
    	$this->rotas['AtendimentoBordadoController/anexar'] = 'atendimento-bordado/anexar';
    	$this->rotas['AtendimentoBordadoController/removerAnexo'] = 'atendimento-bordado/removerAnexo';
    	$this->rotas['AtendimentoBordadoController/adicionarPedido'] = 'atendimento-bordado/adicionarPedido';
    	$this->rotas['AtendimentoBordadoController/removerPedido'] = 'atendimento-bordado/removerPedido';

    	$this->rotas['AtendimentoMatrizBordadoController/initial'] = 'atendimento-conversao';
    	$this->rotas['AtendimentoMatrizBordadoController/editar'] = 'atendimento-conversao/editar';
    	$this->rotas['AtendimentoMatrizBordadoController/editarPedido'] = 'atendimento-conversao/editarPedido';
    	$this->rotas['AtendimentoMatrizBordadoController/editarPelaTabela'] = 'atendimento-conversao/editar-pela-tabela';
    	$this->rotas['AtendimentoMatrizBordadoController/chamar'] = 'atendimento-conversao/chamar';
    	$this->rotas['AtendimentoMatrizBordadoController/iniciar'] = 'atendimento-conversao/iniciar';
    	$this->rotas['AtendimentoMatrizBordadoController/concluir'] = 'atendimento-conversao/concluir';
    	$this->rotas['AtendimentoMatrizBordadoController/excluir'] = 'atendimento-conversao/excluir';
    	$this->rotas['AtendimentoMatrizBordadoController/anexar'] = 'atendimento-conversao/anexar';
    	$this->rotas['AtendimentoMatrizBordadoController/removerAnexo'] = 'atendimento-conversao/removerAnexo';
    	$this->rotas['AtendimentoMatrizBordadoController/adicionarPedido'] = 'atendimento-conversao/adicionarPedido';
    	$this->rotas['AtendimentoMatrizBordadoController/removerPedido'] = 'atendimento-conversao/removerPedido';

    	$this->rotas['AtendimentoVendasController/initial'] = 'atendimento-vendas';
    	$this->rotas['AtendimentoVendasController/editar'] = 'atendimento-vendas/editar';
    	$this->rotas['AtendimentoVendasController/editarPelaTabela'] = 'atendimento-vendas/editar-pela-tabela';
    	$this->rotas['AtendimentoVendasController/chamar'] = 'atendimento-vendas/chamar';
    	$this->rotas['AtendimentoVendasController/iniciar'] = 'atendimento-vendas/iniciar';
    	$this->rotas['AtendimentoVendasController/concluir'] = 'atendimento-vendas/concluir';
    	$this->rotas['AtendimentoVendasController/excluir'] = 'atendimento-vendas/excluir';
    }

    private function setRotaAtendimentoSenha(){

    	$this->rotas['SenhaTriagemController/initial'] = 'senhas';
    	$this->rotas['SenhaTriagemController/recriarTriagemPelaTabela'] = 'senhas/recriarTriagemPelaTabela';

    	$this->rotas['SenhaArteController/initial'] = 'senhas-arte';

    	$this->rotas['SenhaBordadoController/initial'] = 'senhas-bordado';

    	$this->rotas['SenhaMatrizBordadoController/initial'] = 'senhas-conversao';

    	$this->rotas['SenhaVendasController/initial'] = 'senhas-vendas';
    }

    private function setRotaCliente(){
    	$this->rotas['ClienteBase/jsListarClientePorId'] = 'cliente/jsListarPorId';
    	$this->rotas['ClienteBase/jsListarClientePorCnpjCpf'] = 'cliente/jsListarClientePorCnpjCpf';
    	$this->rotas['ClienteBase/jsListarClienteWsPorCnpjCpf'] = 'cliente/jsListarClienteWsPorCnpjCpf';
    	$this->rotas['ClienteBase/jsCriarClientePorWs'] = 'cliente/jsCriarClientePorWs';
    	$this->rotas['ClienteBase/jsPegarCaptchaReceita'] = 'cliente/jsPegarCaptchaReceita';
    	$this->rotas['ClienteBase/jsPegarDadosCnpjComCaptchaReceita'] = 'cliente/jsPegarDadosCnpjComCaptchaReceita';
    	$this->rotas['ClienteBase/jsPegarEnderecoPorCep'] = 'cliente/jsPegarEnderecoPorCep';
    	$this->rotas['ClienteBase/jsPegarCepPorEndereco'] = 'cliente/jsPegarCepPorEndereco';
      $this->rotas['ClienteBase/jsPegarUf'] = 'cliente/jsPegarUf';
      $this->rotas['ClienteBase/jsPegarCidade'] = 'cliente/jsPegarCidade';
      $this->rotas['ClienteBase/jsPegarLogradouro'] = 'cliente/jsPegarLogradouro';
    	$this->rotas['ClienteBase/jsListarClientePorId'] = 'cliente/jsListarClientePorId';
    	$this->rotas['ClienteBase/jsFiltrarCidadePorUf'] = 'cliente/jsFiltrarCidadePorUf';

    	$this->rotas['ClienteTriagemController/initial'] = 'cliente-triagem';
    	$this->rotas['ClienteTriagemController/criar'] = 'cliente-triagem/criar';
    	$this->rotas['ClienteTriagemController/editar'] = 'cliente-triagem/editar';
    	$this->rotas['ClienteTriagemController/criarPelaTabela'] = 'cliente-triagem/criar-pela-tabela';
    }

    private function setRotaComissaoVenda(){
    	$this->rotas['ComissaoVendaController/analiseParaVendedorLogado'] = 'comissao-venda/pedido-a-pedido';
    	$this->rotas['ComissaoVendaController/sinteseParaVendedorLogado'] = 'comissao-venda/resumo';
    	$this->rotas['ComissaoVendaController/analisePorVendedor'] = 'comissao-venda/analise-por-vendedor';
    	$this->rotas['ComissaoVendaController/analisePorVendedorClasse'] = 'comissao-venda/analise-por-vendedor-classe';
    	$this->rotas['ComissaoVendaController/sintesePorVendedor'] = 'comissao-venda/sintese-por-vendedor';
		$this->rotas['ComissaoVendaController/pgto'] = 'comissao-venda/pgto';
    }

    private function setRotaRelatorioProducao(){
    	$this->rotas['RelatorioProducaoController/bordadoVarejo'] = 'relatorio-producao/bordado-varejo';
    	$this->rotas['RelatorioProducaoController/bordadoAtacado'] = 'relatorio-producao/bordado-atacado';
    }

    private function setRotaContabil(){
    	$this->rotas['ContabilController/conciliarCartaoG2'] = 'conciliar-cartao-g2';
    }

    private function setRotaDesenvolvedor(){
    	$this->rotas['DesenvolvedorController/produtoIcon'] = 'desenvolvedor-produto-icon';
    }

    private function setRotaFaturamentoDemanda(){
    	$this->rotas['FaturamentoDemandaController/initial'] = 'faturamento-demanda';
    	$this->rotas['FaturamentoDemandaController/dashboard'] = 'faturamento-demanda/dashboard';
    	$this->rotas['FaturamentoDemandaController/pesquisa'] = 'faturamento-demanda/pesquisa';
    	$this->rotas['FaturamentoDemandaController/baixarPelaTabela'] = 'faturamento-demanda/baixar-pela-tabela';
    	$this->rotas['FaturamentoDemandaController/baixar'] = 'faturamento-demanda/baixar';
    	$this->rotas['FaturamentoDemandaController/desfazerBaixa'] = 'faturamento-demanda/desfazer-baixa';
    	$this->rotas['FaturamentoDemandaController/jsPegarQtdFaturamento'] = 'faturamento-demanda/jsPegarQtdFaturamento';
    }

    private function setRotaLogin(){
    	$this->rotas['LoginController/entrar'] = 'entrar';
    	$this->rotas['LoginController/sair'] = 'sair';
    	$this->rotas['LoginController/jsVerificarLogin'] = 'login/jsVerificarLogin';
    	$this->rotas['LoginController/criarSessaoCacheLimpo'] = 'login/criar-session-cache-limpo';
    }

    private function setRotaGuiche(){
    	$this->rotas['GuicheController/initial'] = 'guiche';
    	$this->rotas['GuicheController/criar'] = 'guiche/criar';
    	$this->rotas['GuicheController/editar'] = 'guiche/editar';
    	$this->rotas['GuicheController/editarPelaTabela'] = 'guiche/editar-pela-tabela';
    	$this->rotas['GuicheController/desativar'] = 'guiche/desativar';
    	$this->rotas['GuicheController/excluir'] = 'guiche/excluir';
    }

    private function setRotaMonitorChamada(){
    	$this->rotas['MonitorChamadaController/initial'] = 'monitor-chamada';
    	$this->rotas['MonitorChamadaController/criar'] = 'monitor-chamada/criar';
    	$this->rotas['MonitorChamadaController/editar'] = 'monitor-chamada/editar';
    	$this->rotas['MonitorChamadaController/editarPelaTabela'] = 'monitor-chamada/editarPelaTabela';
    	$this->rotas['MonitorChamadaController/excluir'] = 'monitor-chamada/excluir';
    	$this->rotas['MonitorChamadaController/desativar'] = 'monitor-chamada/desativar';
    	$this->rotas['MonitorChamadaController/atendimento'] = 'monitor-chamada/atendimento';
    	$this->rotas['MonitorChamadaController/atendimentoVendas'] = 'monitor-chamada/atendimento-vendas';
    	$this->rotas['MonitorChamadaController/atualizarAtendimento'] = 'monitor-chamada/atualizarAtendimento';
    }

    private function setRotaOp(){
    	$this->rotas['OpAutoBordadoController/initial'] = 'op-auto-bordado';
    	$this->rotas['OpAutoBordadoController/criar'] = 'op-auto-bordado/criar';

    	$this->rotas['OpAutoEstampariaController/initial'] = 'op-auto-estamparia';
    	$this->rotas['OpAutoEstampariaController/criar'] = 'op-auto-estamparia/criar';

    	$this->rotas['OpGeralController/initial'] = 'op-geral';
    	$this->rotas['OpGeralController/criarDoPedido'] = 'op-geral/criar-do-pedido';
    	$this->rotas['OpGeralController/criar'] = 'op-geral/criar';
    	$this->rotas['OpGeralController/criarOs'] = 'op-geral/criar-os';
    	$this->rotas['OpGeralController/criarOsDoPedido'] = 'op-geral/criar-os-do-pedido';
    	$this->rotas['OpGeralController/editar'] = 'op-geral/editar';
    	$this->rotas['OpGeralController/editarOs'] = 'op-geral/editar-os';
    	$this->rotas['OpGeralController/editarPelaTabela'] = 'op-geral/editar-pela-tabela';
    	$this->rotas['OpGeralController/editarOsPelaTabela'] = 'op-geral/editar-os-pela-tabela';
    	$this->rotas['OpGeralController/expedirOs'] = 'op-geral/expedir-os';
    	$this->rotas['OpGeralController/dividirOs'] = 'op-geral/dividir-os';
    	$this->rotas['OpGeralController/excluir'] = 'op-geral/excluir';
    	$this->rotas['OpGeralController/excluirOs'] = 'op-geral/excluir-os';

    	$this->rotas['OpBordadoController/initial'] = 'op-bordado';
    	$this->rotas['OpBordadoController/criarDoPedido'] = 'op-bordado/criar-do-pedido';
    	$this->rotas['OpBordadoController/criar'] = 'op-bordado/criar';
    	$this->rotas['OpBordadoController/criarOs'] = 'op-bordado/criar-os';
    	$this->rotas['OpBordadoController/criarOsDoPedido'] = 'op-bordado/criar-os-do-pedido';
    	$this->rotas['OpBordadoController/editar'] = 'op-bordado/editar';
    	$this->rotas['OpBordadoController/editarOs'] = 'op-bordado/editar-os';
    	$this->rotas['OpBordadoController/editarPelaTabela'] = 'op-bordado/editar-pela-tabela';
    	$this->rotas['OpBordadoController/editarOsPelaTabela'] = 'op-bordado/editar-os-pela-tabela';
    	$this->rotas['OpBordadoController/expedirOs'] = 'op-bordado/expedir-os';
    	$this->rotas['OpBordadoController/dividirOs'] = 'op-bordado/dividir-os';
    	$this->rotas['OpBordadoController/excluir'] = 'op-bordado/excluir';
    	$this->rotas['OpBordadoController/excluirOs'] = 'op-bordado/excluir-os';

    	$this->rotas['OpSilkController/initial'] = 'op-silk';
    	$this->rotas['OpSilkController/criarDoPedido'] = 'op-silk/criar-do-pedido';
    	$this->rotas['OpSilkController/criar'] = 'op-silk/criar';
    	$this->rotas['OpSilkController/criarOs'] = 'op-silk/criar-os';
    	$this->rotas['OpSilkController/criarOsDoPedido'] = 'op-silk/criar-os-do-pedido';
    	$this->rotas['OpSilkController/editar'] = 'op-silk/editar';
    	$this->rotas['OpSilkController/editarOs'] = 'op-silk/editar-os';
    	$this->rotas['OpSilkController/editarPelaTabela'] = 'op-silk/editar-pela-tabela';
    	$this->rotas['OpSilkController/editarOsPelaTabela'] = 'op-silk/editar-os-pela-tabela';
    	$this->rotas['OpSilkController/fotolitarOs'] = 'op-silk/fotolitar-os';
    	$this->rotas['OpSilkController/expedirOs'] = 'op-silk/expedir-os';
    	$this->rotas['OpSilkController/dividirOs'] = 'op-silk/dividir-os';
    	$this->rotas['OpSilkController/receberOs'] = 'op-silk/receber-os';
    	$this->rotas['OpSilkController/desfazerRecebimentoOs'] = 'op-silk/desfazer-recebimento-os';
    	$this->rotas['OpSilkController/excluir'] = 'op-silk/excluir';
    	$this->rotas['OpSilkController/excluirOs'] = 'op-silk/excluir-os';
    	$this->rotas['OpSilkController/resumo'] = 'op-silk/resumo';
    }

    private function setRotaPacote(){

    	$this->rotas['PacoteBalancoController/initial'] = 'pacote-balanco';
    	$this->rotas['PacoteBalancoController/criar'] = 'pacote-balanco/criar';
    	$this->rotas['PacoteBalancoController/editar'] = 'pacote-balanco/editar';
    	$this->rotas['PacoteBalancoController/editarPelaTabela'] = 'pacote-balanco/editar-pela-tabela';
    	$this->rotas['PacoteBalancoController/desativar'] = 'pacote-balanco/desativar';
    	$this->rotas['PacoteBalancoController/excluir'] = 'pacote-balanco/excluir';
    	$this->rotas['PacoteBalancoController/adicionarPedido'] = 'pacote-balanco/adicionar-pedido';
    	$this->rotas['PacoteBalancoController/excluirPedido'] = 'pacote-balanco/excluir-pedido';
    	$this->rotas['PacoteBalancoController/entregar'] = 'pacote-balanco/entregar';
    	$this->rotas['PacoteBalancoController/jsEntregarPedido'] = 'pacote-balanco/jsEntregarPedido';
    	$this->rotas['PacoteBalancoController/jsBuscarDescricaoBalanco'] = 'pacote-balanco/jsBuscarDescricaoBalanco';
    	$this->rotas['PacoteBalancoController/jsPegarEtapasRealizadas'] = 'pacote-balanco/jsPegarEtapasRealizadas';
    	$this->rotas['PacoteBalancoController/jsPegarRankingEtapasRealizadas'] = 'pacote-balanco/jsPegarRankingEtapasRealizadas';
    	$this->rotas['PacoteBalancoController/jsListarResumoEntregas'] = 'pacote-balanco/jsListarResumoEntregas';
    	$this->rotas['PacoteBalancoController/jsListarResumoEntregasPorSituacao'] = 'pacote-balanco/jsListarResumoEntregasPorSituacao';
    	$this->rotas['PacoteBalancoController/jsPegarEntregaPorPgto'] = 'pacote-balanco/jsPegarEntregaPorPgto';
    	$this->rotas['PacoteBalancoController/jsPegarQtdEntregues'] = 'pacote-balanco/jsPegarQtdEntregues';
    	$this->rotas['PacoteBalancoController/jsPegarQtdEntreguesPorMunicipio'] = 'pacote-balanco/jsPegarQtdEntreguesPorMunicipio';
    	$this->rotas['PacoteBalancoController/jsPegarQtdEntreguesPorServico'] = 'pacote-balanco/jsPegarQtdEntreguesPorServico';
    	$this->rotas['PacoteBalancoController/dashboard'] = 'pacote-balanco/dashboard';
    	$this->rotas['PacoteBalancoController/aplicarBalancoInicial'] = 'pacote-balanco/aplicarBalancoInicial';

    	$this->rotas['PacoteEntradaController/initial'] = 'pacote-entrada';
    	$this->rotas['PacoteEntradaController/porPedido'] = 'pacote-entrada/por-pedido';
    	$this->rotas['PacoteEntradaController/entradaPelaTabela'] = 'pacote-entrada/entrada-pela-tabela';

    	$this->rotas['PacoteConferenciaController/initial'] = 'pacote-conferencia';
    	$this->rotas['PacoteConferenciaController/conferir'] = 'pacote-conferencia/conferir';
    	$this->rotas['PacoteConferenciaController/conferenciaPelaTabela'] = 'pacote-conferencia/conferencia-pela-tabela';
    	$this->rotas['PacoteConferenciaController/desfazerConferencia'] = 'pacote-conferencia/desfazer-conferencia';

    	$this->rotas['PacoteContagemController/initial'] = 'pacote-contagem';
    	$this->rotas['PacoteContagemController/criarContagem'] = 'pacote-contagem/criar-contagem';
    	$this->rotas['PacoteContagemController/excluirContagem'] = 'pacote-contagem/excluir-contagem';
    	$this->rotas['PacoteContagemController/enviarFaturamento'] = 'pacote-contagem/enviar-faturamento';
    	$this->rotas['PacoteContagemController/desfazerEnvio'] = 'pacote-contagem/desfazer-envio';
    	$this->rotas['PacoteContagemController/separacaoPelaTabela'] = 'pacote-contagem/separacao-pela-tabela';
    	$this->rotas['PacoteContagemController/criarEntrega'] = 'pacote-contagem/criar-entrega-separacao';

    	$this->rotas['PacotePesquisaController/initial'] = 'pacote-pesquisa';
    	$this->rotas['PacotePesquisaController/analise'] = 'pacote-pesquisa/analise';
    	$this->rotas['PacotePesquisaController/jsLoginRapido'] = 'pacote-pesquisa/jsLoginRapido';
    	$this->rotas['PacotePesquisaController/jsPegarContagem'] = 'pacote-pesquisa/jsPegarContagem';
    	$this->rotas['PacotePesquisaController/jsPegarTabelaHistoricoEntrega'] = 'pacote-pesquisa/jsPegarTabelaHistoricoEntrega';
    	$this->rotas['PacotePesquisaController/jsPegarDadosClientePorId'] = 'pacote-pesquisa/jsPegarDadosClientePorId';
    	$this->rotas['PacotePesquisaController/jsPegarDestinatarioEntrega'] = 'pacote-pesquisa/jsPegarDestinatarioEntrega';

    	$this->rotas['PacoteSaidaController/initial'] = 'pacote-saida';
    	$this->rotas['PacoteSaidaController/desfazerEntrega'] = 'pacote-saida/desfazer-entrega';
    	$this->rotas['PacoteSaidaController/entregar'] = 'pacote-saida/entregar';
    	$this->rotas['PacoteSaidaController/saidaPelaTabela'] = 'pacote-saida/saida-pela-tabela';
    	$this->rotas['PacoteSaidaController/criarEntrega'] = 'pacote-saida/criar-entrega-saida';
    }

    private function setRotaPedido(){
    	$this->rotas['PedidoController/jsCriarPedidosTeste'] = 'pedido/js-criar-pedidos-teste';
    }

    private function setRotaPedidoVenda(){
    	$this->rotas['PedidoVendaController/faturamento'] = 'pedido-venda/faturamento';
    	$this->rotas['PedidoVendaController/adicionarFaturamentoAvulso'] = 'pedido-venda/adicionar-faturamento-avulso';
    	$this->rotas['PedidoVendaController/recebimento'] = 'pedido-venda/recebimento';
    	$this->rotas['PedidoVendaController/recebimentoClasse'] = 'pedido-venda/recebimento-classe';
    	$this->rotas['PedidoVendaController/analise'] = 'pedido-venda/analise';
    	$this->rotas['PedidoVendaController/transferirAdiantamento'] = 'pedido-venda/transferir-adiantamento';
    	$this->rotas['PedidoVendaController/jsPegarPedidoAdiantamento'] = 'pedido-venda/jsPegarPedidoAdiantamento';
    	$this->rotas['PedidoVendaController/jsPegarHistoricoAdiantamento'] = 'pedido-venda/jsPegarHistoricoAdiantamento';
    	$this->rotas['PedidoVendaController/jsPegarHistoricoProducao'] = 'pedido-venda/jsPegarHistoricoProducao';
    	$this->rotas['PedidoVendaController/jsPegarHistoricoEntrega'] = 'pedido-venda/jsPegarHistoricoEntrega';
    }

    private function setRotaPendenciaVenda(){
    	$this->rotas['PendenciaVendaController/analiticoPorVendedorLogado'] = 'minha-pendencia-venda/initial';
    	$this->rotas['PendenciaVendaController/analitico'] = 'pendencia-venda/analitico';
    	$this->rotas['PendenciaVendaController/sintetico'] = 'pendencia-venda/resumo';
    	$this->rotas['PendenciaVendaController/cancelar'] = 'pendencia-venda/cancelar';
    }

    private function setRotaPermissao(){
    	$this->rotas['PermissaoController/initial'] = 'permissao-acesso';
    	$this->rotas['PermissaoController/criar'] = 'permissao-acesso/criar';
    	$this->rotas['PermissaoController/editar'] = 'permissao-acesso/editar';
    	$this->rotas['PermissaoController/editarPelaTabela'] = 'permissao-acesso/editar-pela-tabela';
    	$this->rotas['PermissaoController/definir'] = 'permissao-acesso/definir';
    	$this->rotas['PermissaoController/desativar'] = 'permissao-acesso/desativar';
    	$this->rotas['PermissaoController/excluir'] = 'permissao-acesso/excluir';
    }

    private function setRotaProducao(){
    	$this->rotas['ProducaoArteEntradaController/initial'] = 'entrada-producao-arte';
    	$this->rotas['ProducaoArteEntradaController/entradaPorOs'] = 'entrada-producao-arte/entrada-por-os';
    	$this->rotas['ProducaoArteEntradaController/editar'] = 'entrada-producao-arte/editar';
    	$this->rotas['ProducaoArteEntradaController/entradaPorPedido'] = 'entrada-producao-arte/entrada-por-pedido';
    	$this->rotas['ProducaoArteEntradaController/entradaPorLinhaDaTabela'] = 'entrada-producao-arte/entrada-pela-tabela';
    	$this->rotas['ProducaoArteEntradaController/retornarOsParaEdicao'] = 'entrada-producao-arte/retornar-os-edicao';
    	$this->rotas['ProducaoArteEntradaController/editarPorLinhaDaTabela'] = 'entrada-producao-arte/editar-pela-tabela';

    	$this->rotas['ProducaoArteSaidaController/initial'] = 'saida-producao-arte';
    	$this->rotas['ProducaoArteSaidaController/saidaPorOs'] = 'saida-producao-arte/saida-por-os';
    	$this->rotas['ProducaoArteSaidaController/editar'] = 'saida-producao-arte/editar';
    	$this->rotas['ProducaoArteSaidaController/saidaPorPedido'] = 'saida-producao-arte/saida-por-pedido';
    	$this->rotas['ProducaoArteSaidaController/saidaPorLinhaDaTabela'] = 'saida-producao-arte/saida-pela-tabela';
    	$this->rotas['ProducaoArteSaidaController/desfazerSaida'] = 'saida-producao-arte/desfazer-saida';
    	$this->rotas['ProducaoArteSaidaController/editarPorLinhaDaTabela'] = 'saida-producao-arte/editar-pela-tabela';

    	$this->rotas['ProducaoBordadoAtacadoEntradaController/initial'] = 'entrada-producao-bordado-atacado';
    	$this->rotas['ProducaoBordadoAtacadoEntradaController/entradaPorOs'] = 'entrada-producao-bordado-atacado/entrada-por-os';
    	$this->rotas['ProducaoBordadoAtacadoEntradaController/editar'] = 'entrada-producao-bordado-atacado/editar';
    	$this->rotas['ProducaoBordadoAtacadoEntradaController/entradaPorPedido'] = 'entrada-producao-bordado-atacado/entrada-por-pedido';
    	$this->rotas['ProducaoBordadoAtacadoEntradaController/entradaPorLinhaDaTabela'] = 'entrada-producao-bordado-atacado/entrada-pela-tabela';
    	$this->rotas['ProducaoBordadoAtacadoEntradaController/retornarOsParaEdicao'] = 'entrada-producao-bordado-atacado/retornar-os-edicao';
    	$this->rotas['ProducaoBordadoAtacadoEntradaController/editarPorLinhaDaTabela'] = 'entrada-producao-bordado-atacado/editar-pela-tabela';

    	$this->rotas['ProducaoBordadoAtacadoSaidaController/initial'] = 'saida-producao-bordado-atacado';
    	$this->rotas['ProducaoBordadoAtacadoSaidaController/saidaPorOs'] = 'saida-producao-bordado-atacado/saida-por-os';
    	$this->rotas['ProducaoBordadoAtacadoSaidaController/editar'] = 'saida-producao-bordado-atacado/editar';
    	$this->rotas['ProducaoBordadoAtacadoSaidaController/saidaPorPedido'] = 'saida-producao-bordado-atacado/saida-por-pedido';
    	$this->rotas['ProducaoBordadoAtacadoSaidaController/saidaPorLinhaDaTabela'] = 'saida-producao-bordado-atacado/saida-pela-tabela';
    	$this->rotas['ProducaoBordadoAtacadoSaidaController/desfazerSaida'] = 'saida-producao-bordado-atacado/desfazer-saida';
    	$this->rotas['ProducaoBordadoAtacadoSaidaController/editarPorLinhaDaTabela'] = 'saida-producao-bordado-atacado/editar-pela-tabela';

    	$this->rotas['ProducaoBordadoVarejoEntradaController/initial'] = 'entrada-producao-bordado-varejo';
    	$this->rotas['ProducaoBordadoVarejoEntradaController/entradaPorOs'] = 'entrada-producao-bordado-varejo/entrada-por-os';
    	$this->rotas['ProducaoBordadoVarejoEntradaController/editar'] = 'entrada-producao-bordado-varejo/editar';
    	$this->rotas['ProducaoBordadoVarejoEntradaController/entradaPorPedido'] = 'entrada-producao-bordado-varejo/entrada-por-pedido';
    	$this->rotas['ProducaoBordadoVarejoEntradaController/entradaPorLinhaDaTabela'] = 'entrada-producao-bordado-varejo/entrada-pela-tabela';
    	$this->rotas['ProducaoBordadoVarejoEntradaController/retornarOsParaEdicao'] = 'entrada-producao-bordado-varejo/retornar-os-edicao';
    	$this->rotas['ProducaoBordadoVarejoEntradaController/editarPorLinhaDaTabela'] = 'entrada-producao-bordado-varejo/editar-pela-tabela';

    	$this->rotas['ProducaoBordadoVarejoSaidaController/initial'] = 'saida-producao-bordado-varejo';
    	$this->rotas['ProducaoBordadoVarejoSaidaController/saidaPorOs'] = 'saida-producao-bordado-varejo/saida-por-os';
    	$this->rotas['ProducaoBordadoVarejoSaidaController/editar'] = 'saida-producao-bordado-varejo/editar';
    	$this->rotas['ProducaoBordadoVarejoSaidaController/saidaPorPedido'] = 'saida-producao-bordado-varejo/saida-por-pedido';
    	$this->rotas['ProducaoBordadoVarejoSaidaController/saidaPorLinhaDaTabela'] = 'saida-producao-bordado-varejo/saida-pela-tabela';
    	$this->rotas['ProducaoBordadoVarejoSaidaController/desfazerSaida'] = 'saida-producao-bordado-varejo/desfazer-saida';
    	$this->rotas['ProducaoBordadoVarejoSaidaController/editarPorLinhaDaTabela'] = 'saida-producao-bordado-varejo/editar-pela-tabela';

    	$this->rotas['ProducaoEstampariaEntradaController/initial'] = 'entrada-producao-estamparia';
    	$this->rotas['ProducaoEstampariaEntradaController/entradaPorOs'] = 'entrada-producao-estamparia/entrada-por-os';
    	$this->rotas['ProducaoEstampariaEntradaController/editar'] = 'entrada-producao-estamparia/editar';
    	$this->rotas['ProducaoEstampariaEntradaController/entradaPorPedido'] = 'entrada-producao-estamparia/entrada-por-pedido';
    	$this->rotas['ProducaoEstampariaEntradaController/entradaPorLinhaDaTabela'] = 'entrada-producao-estamparia/entrada-pela-tabela';
    	$this->rotas['ProducaoEstampariaEntradaController/retornarOsParaEdicao'] = 'entrada-producao-estamparia/retornar-os-edicao';
    	$this->rotas['ProducaoEstampariaEntradaController/editarPorLinhaDaTabela'] = 'entrada-producao-estamparia/editar-pela-tabela';

    	$this->rotas['ProducaoEstampariaSaidaController/initial'] = 'saida-producao-estamparia';
    	$this->rotas['ProducaoEstampariaSaidaController/saidaPorOs'] = 'saida-producao-estamparia/saida-por-os';
    	$this->rotas['ProducaoEstampariaSaidaController/editar'] = 'saida-producao-estamparia/editar';
    	$this->rotas['ProducaoEstampariaSaidaController/saidaPorPedido'] = 'saida-producao-estamparia/saida-por-pedido';
    	$this->rotas['ProducaoEstampariaSaidaController/saidaPorLinhaDaTabela'] = 'saida-producao-estamparia/saida-pela-tabela';
    	$this->rotas['ProducaoEstampariaSaidaController/desfazerSaida'] = 'saida-producao-estamparia/desfazer-saida';
    	$this->rotas['ProducaoEstampariaSaidaController/editarPorLinhaDaTabela'] = 'saida-producao-estamparia/editar-pela-tabela';

    	$this->rotas['ProducaoMatrizBordadoEntradaController/initial'] = 'entrada-producao-conversao';
    	$this->rotas['ProducaoMatrizBordadoEntradaController/entradaPorOs'] = 'entrada-producao-conversao/entrada-por-os';
    	$this->rotas['ProducaoMatrizBordadoEntradaController/editar'] = 'entrada-producao-conversao/editar';
    	$this->rotas['ProducaoMatrizBordadoEntradaController/entradaPorPedido'] = 'entrada-producao-conversao/entrada-por-pedido';
    	$this->rotas['ProducaoMatrizBordadoEntradaController/entradaPorLinhaDaTabela'] = 'entrada-producao-conversao/entrada-pela-tabela';
    	$this->rotas['ProducaoMatrizBordadoEntradaController/retornarOsParaEdicao'] = 'entrada-producao-conversao/retornar-os-edicao';
    	$this->rotas['ProducaoMatrizBordadoEntradaController/editarPorLinhaDaTabela'] = 'entrada-producao-conversao/editar-pela-tabela';

    	$this->rotas['ProducaoMatrizBordadoSaidaController/initial'] = 'saida-producao-conversao';
    	$this->rotas['ProducaoMatrizBordadoSaidaController/saidaPorOs'] = 'saida-producao-conversao/saida-por-os';
    	$this->rotas['ProducaoMatrizBordadoSaidaController/editar'] = 'saida-producao-conversao/editar';
    	$this->rotas['ProducaoMatrizBordadoSaidaController/saidaPorPedido'] = 'saida-producao-conversao/saida-por-pedido';
    	$this->rotas['ProducaoMatrizBordadoSaidaController/saidaPorLinhaDaTabela'] = 'saida-producao-conversao/saida-pela-tabela';
    	$this->rotas['ProducaoMatrizBordadoSaidaController/desfazerSaida'] = 'saida-producao-conversao/desfazer-saida';
    	$this->rotas['ProducaoMatrizBordadoSaidaController/editarPorLinhaDaTabela'] = 'saida-producao-conversao/editar-pela-tabela';

    	$this->rotas['ProducaoSilkEntradaController/initial'] = 'entrada-producao-silk';
    	$this->rotas['ProducaoSilkEntradaController/entradaPorOs'] = 'entrada-producao-silk/entrada-por-os';
    	$this->rotas['ProducaoSilkEntradaController/editar'] = 'entrada-producao-silk/editar';
    	$this->rotas['ProducaoSilkEntradaController/entradaPorPedido'] = 'entrada-producao-silk/entrada-por-pedido';
    	$this->rotas['ProducaoSilkEntradaController/entradaPorLinhaDaTabela'] = 'entrada-producao-silk/entrada-pela-tabela';
    	$this->rotas['ProducaoSilkEntradaController/retornarOsParaEdicao'] = 'entrada-producao-silk/retornar-os-edicao';
    	$this->rotas['ProducaoSilkEntradaController/editarPorLinhaDaTabela'] = 'entrada-producao-silk/editar-pela-tabela';

    	$this->rotas['ProducaoSilkSaidaController/initial'] = 'saida-producao-silk';
    	$this->rotas['ProducaoSilkSaidaController/saidaPorOs'] = 'saida-producao-silk/saida-por-os';
    	$this->rotas['ProducaoSilkSaidaController/editar'] = 'saida-producao-silk/editar';
    	$this->rotas['ProducaoSilkSaidaController/saidaPorPedido'] = 'saida-producao-silk/saida-por-pedido';
    	$this->rotas['ProducaoSilkSaidaController/saidaPorLinhaDaTabela'] = 'saida-producao-silk/saida-pela-tabela';
    	$this->rotas['ProducaoSilkSaidaController/desfazerSaida'] = 'saida-producao-silk/desfazer-saida';
    	$this->rotas['ProducaoSilkSaidaController/editarPorLinhaDaTabela'] = 'saida-producao-silk/editar-pela-tabela';

    }

    private function setRotaProdutoServicoIncluso(){
    	$this->rotas['ProdutoServicoInclusoController/initial'] = 'produto-servico-incluso';
    	$this->rotas['ProdutoServicoInclusoController/criar'] = 'produto-servico-incluso/criar';
    	$this->rotas['ProdutoServicoInclusoController/editar'] = 'produto-servico-incluso/editar';
    	$this->rotas['ProdutoServicoInclusoController/editarPelaTabela'] = 'produto-servico-incluso/editar-pela-tabela';
    	$this->rotas['ProdutoServicoInclusoController/desativar'] = 'produto-servico-incluso/desativar';
    	$this->rotas['ProdutoServicoInclusoController/excluir'] = 'produto-servico-incluso/excluir';
    }

    private function  setRotaServico(){
    	$this->rotas['ServicoController/initial'] = 'servico';
    	$this->rotas['ServicoController/criar'] = 'servico/criar';
    	$this->rotas['ServicoController/editar'] = 'servico/editar';
    	$this->rotas['ServicoController/editarPelaTabela'] = 'servico/editar-pela-tabela';
    	$this->rotas['ServicoController/excluir'] = 'servico/excluir';
    	$this->rotas['ServicoController/desativar'] = 'servico/desativar';
    }

    private function setRotaServicoGrupo(){
    	$this->rotas['ServicoGrupoController/initial'] = 'servico-grupo';
    	$this->rotas['ServicoGrupoController/criar'] = 'servico-grupo/criar';
    	$this->rotas['ServicoGrupoController/editar'] = 'servico-grupo/editar';
    	$this->rotas['ServicoGrupoController/editarPelaTabela'] = 'servico-grupo/editar-pela-tabela';
    	$this->rotas['ServicoGrupoController/excluir'] = 'servico-grupo/excluir';
    	$this->rotas['ServicoGrupoController/desativar'] = 'servico-grupo/desativar';
    }

    private function setRotaServicoPreco(){
    	$this->rotas['ServicoPrecoController/initial'] = 'servico-preco';
    	$this->rotas['ServicoPrecoController/criar'] = 'servico-preco/criar';
    	$this->rotas['ServicoPrecoController/editar'] = 'servico-preco/editar';
    	$this->rotas['ServicoPrecoController/editarPelaTabela'] = 'servico-preco/editar-pela-tabela';
    	$this->rotas['ServicoPrecoController/excluir'] = 'servico-preco/excluir';
    	$this->rotas['ServicoPrecoController/desativar'] = 'servico-preco/desativar';
    }

    private function setRotaSetor(){
    	$this->rotas['SetorController/initial'] = 'setor';
    	$this->rotas['SetorController/criar'] = 'setor/criar';
    	$this->rotas['SetorController/editar'] = 'setor/editar';
    	$this->rotas['SetorController/editarPelaTabela'] = 'setor/editar-pela-tabela';
    	$this->rotas['SetorController/excluir'] = 'setor/excluir';
    	$this->rotas['SetorController/desativar'] = 'setor/desativar';
    }

    private function setRotaTesteAsind(){
    	$this->rotas['TesteAsindFrontEndController/initial'] = 'teste-frontend';
    }

    private function setRotaTriagem(){
    	$this->rotas['TriagemController/initial'] = 'triagem';
    	$this->rotas['TriagemController/novo'] = 'triagem/novo';
    	$this->rotas['TriagemController/editar'] = 'triagem/editar';
    	$this->rotas['TriagemController/excluir'] = 'triagem/excluir';
    	$this->rotas['TriagemController/concluir'] = 'triagem/concluir';
    	$this->rotas['TriagemController/desativar'] = 'triagem/desativar';
    	$this->rotas['TriagemController/jsListarTriagemPorClienteId'] = 'triagem/jsListarTriagemPorClienteId';
    	$this->rotas['TriagemController/jsListarGuichePorSetorId'] = 'triagem/jsListarGuichePorSetorId';
    }

    private function setRotaUnidade(){
    	$this->rotas['UnidadeController/initial'] = 'unidade';
    	$this->rotas['UnidadeController/criar'] = 'unidade/criar';
    	$this->rotas['UnidadeController/editar'] = 'unidade/editar';
    	$this->rotas['UnidadeController/editarPelaTabela'] = 'unidade/editar-pela-tabela';
    	$this->rotas['UnidadeController/excluir'] = 'unidade/excluir';
    	$this->rotas['UnidadeController/desativar'] = 'unidade/desativar';
    }

    private function setRotaUsuario(){
    	$this->rotas['UsuarioController/initial']  = 'usuario';
    	$this->rotas['UsuarioController/criar']  = 'usuario/criar';
    	$this->rotas['UsuarioController/editar']  = 'usuario/editar';
    	$this->rotas['UsuarioController/desativar']  = 'usuario/desativar';
    	$this->rotas['UsuarioController/excluir']  = 'usuario/excluir';
    	$this->rotas['UsuarioController/redefinirSenha']  = 'usuario/redefinir-senha';
    	$this->rotas['UsuarioController/trocarSenha']  = 'usuario/trocar-senha';
    	$this->rotas['UsuarioController/trocarPin']  = 'usuario/trocar-pin';
    	$this->rotas['UsuarioController/jsListarPorUsuarioLogin']  = 'usuario/jsListarPorUsuarioLogin';
    	$this->rotas['UsuarioController/jsListarVendedorPorFuncionarioId']  = 'usuario/jsListarVendedorPorFuncionarioId';
    	$this->rotas['UsuarioController/jsVerificarExistenciaPin'] = 'usuario/jsVerificarExistenciaPin';
    }

    private function setRotaWsSync(){
    	$this->rotas['WsSyncController/cliente'] = 'ws-sync/cliente';
    	$this->rotas['WsSyncController/pedido'] = 'ws-sync/pedido';
    	$this->rotas['WsSyncController/produto'] = 'ws-sync/produto';
    	$this->rotas['WsSyncController/produtoSku'] = 'ws-sync/produto-sku';
    	$this->rotas['WsSyncController/pedidoPorPeriodo'] = 'ws-sync/pedido-por-periodo';
    	$this->rotas['WsSyncController/pedidoPorUltimaAlteracao'] = 'ws-sync/pedido-por-ultima-alteracao';
    	$this->rotas['WsSyncController/pedidoInconformidade'] = 'ws-sync/pedido-inconformidade';
    	$this->rotas['WsSyncController/jsAtualizarStatusPedidoWs'] = 'ws-sync/jsAtualizarStatusPedidoWs';
    	$this->rotas['WsSyncController/jsAnalisarInconformidade'] = 'ws-sync/jsAnalisarInconformidade';
    	$this->rotas['WsSyncController/removerFaturamentosDuplicados'] = 'ws-sync/remover-faturamentos-duplicados';
    	$this->rotas['WsSyncController/produtoPorCsv'] = 'ws-sync/produto-por-csv';
    	$this->rotas['WsSyncController/devolucaoPorCsv'] = 'ws-sync/devolucao-por-csv';
    	$this->rotas['WsSyncController/creditoClientePorCsv'] = 'ws-sync/credito-cliente-por-csv';
    }

}
