## Sistema para atendimneto na pizzaria, criado para trabalhar em conjunto com chatboot da pizzaria no messenger.

### Resumo
Esse sistema foi criado para fornecer um chatboot no messenger para pizzarias.  
Esse sistema possui os seguintes cadastros:  
Clientes, forma de pagamento, taxas de entrega, horario de atendimento, bebidas, pizzas, promoção, configurações gerais, dados cadastrais.  
[https://github.com/AlexandreTomasi/pizza-api/blob/master/image/menu.JPG](https://github.com/AlexandreTomasi/pizza-api/blob/master/image/menu.JPG)  
####Tela de Pedidos:
A tela de pedidos lista todos os pedidos solicitados por um periodo padrao de 3 dias podendo ser alterado.  
A tela de pedidos atualiza a cadas 10 segundos para verificar se chegaram novos pedidos, quando chegar um novo pedido o atendente  
poderá atender ou cancelar esse pedido. Quando clicar em atender sera aberto um modal com todos os dados do pedido no fim ao  
clicar em iniciar atendimento será enviado para a pessoa que solicitou o pedido via messenger uma reposta dizendo que o pedido  
foi recebido e está sendo preparado. Não avendo necessidade nenhuma do atendente ter que acessar o messenger.  
O atendente poderá tambem pausar o bot caso a pizzaria estiver com muitos pedidos, clicando no botaão flutuante "ON", podendo  
assim pausar o bot pelo tempo desejado ou reinicialo.  
[https://github.com/AlexandreTomasi/pizza-api/blob/master/image/tela de pedidos.JPG](https://github.com/AlexandreTomasi/pizza-api/blob/master/image/tela de pedidos.JPG)



### Arquitetura
- PHP 
- Framework Codeigniter
- AngularJs
- Mysql 
- Windows 7

### Ferramentas
- Netbeans
- Xampp

### Configuração inicial
**Para executar o projeto deve existir um banco chamado “skybots_pizzaria” e “skybots_gerencia”** usando login padrão:  
Abra o phpMyAdmin e abra a aba de SQL desse banco, execute esse sql: [skybots_gerencia.sql](https://github.com/AlexandreTomasi/pizza-api/blob/master/skybots_gerencia.sql)  
e execute esse sql: [skybots_pizzaria.sql](https://github.com/AlexandreTomasi/pizza-api/blob/master/skybots_pizzaria.sql)  

### Iniciando/Autenticando
- Acesse no navegador [http://[::1]/pizza-api/](http://[::1]/pizza-api/) e faça o login:  
logim: veteranama@gmail.com  
senha: 12345  





