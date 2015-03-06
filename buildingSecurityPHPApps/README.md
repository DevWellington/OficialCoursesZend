# Zend Course - Building Security into your Application

Code Education | [Link do Curso](http://sites.code.education/seguranca-com-php/)

###Conteúdo Programático

1. CONCEITOS BÁSICOS DE SEGURANÇA

    - O que é segurança
    - Defesa a fundo
    - Regras básicas de segurança
    - Guidelines para criar aplicações web seguras

2. O QUE ACONTECE QUANDO AS COISAS DÃO ERRADA
    
    - SQL Injection Attacks
    - XSS / XST Injection Attacks
    - Command Injection Attacks
    - Remote Code Injection Attacks
    - XSRF / CSRF Attacks
    - Session Attacks
    - Secure File Uploads

3. OUTRAS MEDIDAS COMUNS DE SEGURANÇA
    
    - Configurações de segurança
    - Segurança com passwords
    - Sandboxes and Traps
    - Tarpits
    - Obscuridade
    - Segurança e AJAX
    - Filtrando Charsets

4. RECURSOS DE SEGURANÇA, FERRAMENTAS E INFRA-ESTRUTURA

    - Recursos e ferramentas de segurança
    - Infraestrutura e segurança de servidores
    - Segurança com o MySQL
    - Revisão do curso e projeto final prático
    
    
## ## Minhas Anotações ## ##
    
## Guidelines

Ataques mais comuns:

- SQL Injection
- Cross-Site Scripting
    - Existem 2 tipos
    
- Função para filtro e validação de variaveis:
    - filter_var
    
- Tratamento de erros, exibe o caminho dos seus arquivos, cuidado.
- Cuidado com dados expostos (visiveis);

- Ferramenta de medição de Banco de Dados ([JMeter](http://jmeter.apache.org/usermanual/build-db-test-plan.html))


**Basic Security Rules**

Nunca confie em um dado que vem de fora da sua aplicação    

- Formulários
- GET/POST data
- Cookies
- Environment Variables
- Server Variables
- HTTP headers
      
Sempre valide a entrada e escape a saída dos dados;

```php
<?php
$dado = valida($dado);
echo escape($dado);
```

Garanta as permissoes para os usuarios de acordo com a necessidade da acplicacao, considerando os possiveis ataques.
    
- Permissoes de acesso a: 
    
        - Base de Dados 
        	- usuarios especificos para as aplicacoes externas
        - Diretorios 
        	- Permissoes considerando sempre a seguranca da aplicacao
        - Permisoes nos diretorios 
        	- Nunca utilize 777, e sempre considere o XX0 
        	- Usuario anonimo nao deve fazer nada na aplicacao
        
**Cookies**

Nao armazene um usuario e senha nos cookies (sao dados sensiveis)

**Errors**

Somente utilize o modo de debug (display_erros, log_errors) em  desenvolvimento.

Utilize os errors nos arquivos de log.

Biblioteca de Logs: MonoLog

O Log também pode derrubar a aplicação

    - Se o disco (HD) chegar ao limite disponível.
    
**Data**

Considere restricoes dos dados utilizando type casting ($id = (int) $_GET['id']))

    - Ignorando erro de SQL

Todo dado trafegado por GET ou POST eh uma string, sempre considere tratar a conversao dos dados;

    - Valor ID (int) 
    - Valor do tipo moeda (float)
    - etc

Encrypt os dados transmitidos

Nao grave dados sensiveis (usuario, senha, arquivos de configuracao, etc) na pasta Publica do Servidor Web (htdocs, public, etc).


**Diagnostico de Arquivos**
    
Debug, var_dump (com ob_start, print_r($var, true), etc)

    - remove phpinfo.php
    - link de downloads dos arquivos que devem ser protegidos
        - Example: (file: slide11.php)
        
    - Não utilize autenticacao baseada SOMENTE no endereço IP
        - $_SERVER['HTTP_REFERRER']
        - Motivo: easy to spoof
        
    - Cuidado com sistemas com usuario e senha padrão
        - Exemplo: PHPMyAdmin / Zabbix / etc
        
**Crie configurações Seguras**

    - Arquivos XML, YML - Fora do diretorio Web

## Modulo 2 - Tipos de Ataques

- Code: SQL, XSS
- Command
- Remote Code

Example:

- site.com/?pagina=contato.php 

**SQL Injection Attacks**

Qualquer input do usuário, não apenas por formulario

Tipos de ataque que podem acontecer:

- Baixar ou apagar todo conteudo da Base de Dados
- Corromper a estrutura da Base (ALTER TABLE)
- Alterar um dado (SENHA)
- DOS (Denial of Service, pool de conexoes, etc)
    - Dica: Camada de Cache (Sistema chave/valor)
        - Não entendi bem qual o sistema indicado pelo Kinn, abri uma pergunta no 360º

- Example: ***Slide 16***

#####Medidas de segurança

- Controlar o tipo do dado que esta sendo enviado pelo formulario;
    - $id = (int) $_GET['id'];

- Para valores do tipo senha, email, etc;
    - filter_var

- Para registros para o banco de dados;
    - mysqli_real_escape_string(); -- Somente para MySQL
    - PDO::quote(); -- Baseada no banco configurado
    - addslashes(); -- Não eh suficiente
    
Exemplo

    - Query de login (limit 1);


**XSS/XST Injection Attacks**

**XSS**: Cross-Site Scripting são ataques de injeção de codigos HTML, CSS ou JavaScript;

- Exemplo: 
    - Um JS que pega um Cookie do cliente, um css que esconde todo o conteudo da pagina;
    - Enviar um ajax para outro servidor;
    - Roda do lado do Cliente;

**XST**: Roubar os Cookies;

- Exemplo: 
    - Detalhes gerados pelos erros de um formulario;
    - Injetar um css que mostra uma propaganda no seu site;
    - Injeta um HTML redirect para um site malicioso;
    - Link enviado por email, spoofing;


#####Medidas de segurança

- Escapar os dados antes de jogar na tela
    - htmlspecialchars() - converte as tags HMTL em ASCII (& = ASCII Code);
    - htmlentities() - semelhante ao htmlspecialchars, mas somente com caracteres que sejam HTML (& = &);
    - strip_tags() - remove tags (porem strip_tags($dado) - remove todos / strip_tags($dado, "<a>") - remove tudo, menos <a>);
    - Zend_Filter_HtmlEntities() - semelhante ao htmlentities, mas com algumas novas soluções.
    
SEMPRE VALIDE O **INPUT** E ESCAPE O **OUTPUT**


Quando for utilizar a strip_tags(), opte por utilizar uma Whitelist ao invez da Blacklist;


**Command Injection Attacks (shell)**

Ataque de injeção de comandos

shell_exec(), exec(), system(), `ls` (`comando`)

- É necessário filtrar;
- Caso sofra um ataque o attacker pode excluir todos os arquivos que o wwwdata (apache) tenha acesso;
- Parecido com SQL Injection, mas no server (bash);

Exemplos no *slide 27*

#####Medidas de segurança

- O PHP provê duas funções para combater estes tipos de ataques:
    - escapeshellarg() - remover qualquer caracter que seja prejudicial (e.g. rm, ls, tar, etc.)
    - escapeshellcmd() - nao remove todos os caracters, somente possíveis comandos que possa prejudicar (e.g. rm -rf);

- Interessante utilizar a combinação;

- Open Stack, ligar VMs

**Remote Code Injection Attack**

Ataques de injeção de codigos PHP na sua aplicação, fazendo uso das funções **include* ** e **require* **. Uma vez que a aplicação possiblita atravez do metodo GET (e.g. ?pagina=contato.php) fazer a inclusão do arquivo.

- As URLs são vuneraveis por permitir incluir arquivos;
- Pode também fazer uso da função **eval()**
    - eval() - possibilita executar comandos PHP. (e.g. eval(echo 'hello';);
    - Somente utilize eval() para gerar codigo PHP (e.g. scaffolding);
    
- Não utilize o preg_replace() com o /e, para que não permita execução de codigos maliciosos;
    - Para o tratamento de imagens, utilize o Imagick => ImageMagick;

- create_function() - possibilita criar funções em tempo de execução;


Exemplos nos *slides 30, 31, 32*

**XSRF/CSRF Attacks**

Cross-Site Request Forgeries, input de dados por formulario;

Exemplo:

```html
<form action="processa_contato.php" method="POST"> 
	<input type="email" name="email" />
	<input type="text" name="texto" />
</form>
```

site.com.br/contato.php

Qualquer site/script consegue enviar os dados para o arquivo:
	action="processa_contato.php"

- Não é seguro filtrar e validar os dados somente no FrontEnd;
- Sempre deve-se validar os dados no BackEnd;

**XSRF**

- Vitima: SITE
    
**XSS**

- Vitima: USUARIO   

Exemplo no *Slide 38 e 39*

#####Medidas de segurança

- Utilizar um token único como forma de input do usuário;

Exemplo no arquivo **slide40.php**


**Session Attacks**

Existem dois tipos de ataques de sessão: Session Hijacking e Session Fixation;

- **Session Hijacking**: Rouba a sessão, atravez do ID da sessão;
    
- **Session Fixation**: Se basea em não regerar a sessão;

#####Medidas de segurança

- Usar criptografia para o login SSL
- Assinalar uma chave escondida para o login
- Checagem por IP (desconsidere o NAT)
- Time Out da Sessão
- Exigir o LogOut
- Autenticação e Session ID
- Invalidar Session ID inexistente
- Regere o ID da sessao: session_regenerate_id():

Passos geração da Sessão:

```shell
Navegador -> Servidor
Servidor -> salva no lado servidor (na sessao do usuario)
--> manda um ID de Sessao para o Cliente
Cliente -> Salva o ID da sessao em um Cookie

Facebook -> altera o id da sessao com o seu id da sessao

XYZ -> aqui estao seus dados.

session_regenerate_id();
```

session_regenerate_id(true), com o parametro booleano true, ele destroi o ID da sessão antigo;

**Ponto negativo** session_regenerate_id, gera o dobro de I/O no servidor

- Soluções para salvar sessão: Memcache, Reddiz, Reach, MongoDB, etc;


**Secure File Uploads**

- $_FILES: Sempre filtre e valide os dados vindos neste array SuperGlobal;
- O MIME type pode ser forjado e engana o PHP;
- Para tratamento de arquivos, considere utilizar as funções: ** *_uploaded_file();**

Exercicio risco de segurança *Slide 45*

- Correção no arquivo **slide45.php**

#####Medidas de segurança

- Utilize a função is_uploaded_file() para verificar se o arquivo existe;
- Para mover os arquivos nos diretorios utilize a move_uploaded_file();

## Modulo 3 - Outras questões de segurança

- **register_globals** OFF
- **display_errors** OFF, **log_errors** ON
- **allow_url_include** OFF
- **error_reporting** E_ALL

**Password Security**

- **Control Aging**: Solicite a alteração da senha por período.
- **Controle de Conteúdo**: Cuidado com caracteres especiais.
- **No Hard-Coding**: Cuidado com os arquivos com dados sensíveis duplicados.
- **Não guarde dados sensíveis em texto puro**: Utilize hash crip and decript;

Projeto de segurança **PHPSEC**

**Sandboxes / Traps** 

*Wikipedia*: **HoneyPot **(tradução livre para o português, Pote de Mel) é uma ferramenta que tem a função de propositalmente simular falhas de segurança de um sistema.

- Utilize os Sandboxes também como armadinha, onde o atacante consegue acesso a uma armadilha no seu sistema;
- Não deixe visivel a parte administrativa do seu sistema.
- Não utilize o sistema.com/admin - considere utilizar algo que nao seja óbvio (sistema.com/cn7y587)
    - "Não adianta ele ter a chave se ele nao sabe onde fica a porta";

**Segurança é melhor que obsurcuridade**

- "Não adianta esconder o dinheiro em baixo do colchão se você deixa a porta aberta".
- Lembre-se: "Nenhuma medida de segurança é suficiente por si só. Passa a ser suficiente quando você faz uma combinação destas medidas".

**Security Implications for Ajax**

- Permite ações complexas sem a ação do Usuario.
- Devemos segurar a aplicação para Code Injection, Session Hijacking, etc.

## Modulo 4

**Security Resources and Tools**

- Dicas importantes:
    - php.net/../security.php
    - apache.org/../security_tips.html
    - linuxsecurity.com/../9/161
    
- Ferramentas:
    - Tripwire
    - IPTables (Firewall Linux)
    - SELinux (Sistema de diretivas)
    - Wireshark or TCPDump (Sniffing de rede, verifica tudo que estão enviando pela rede)

**Secure Infrastructure**

- Isole as bases de dados para locais onde não eh necessario ter acesso.
- Controle o acesso as portas do servidor web.

**Apache / Web Server Security**

- Somente rode o web server com o usuario/grupo expecifico
- `<direcotory>`
- mod_rewrite
- Previnir crawling
    - bloquear acessos simultaneos do mesmo client;

**Database / MySQL Security**

- Mude a senha Padrão;
- Crie usuarios para os modulos especificos quando necessario;
- Leia os logs do banco;






























