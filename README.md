# Título do Projeto

**Desafio Fullstack Nido - Coodesh**

## Descrição

Este projeto é uma aplicação fullstack desenvolvida como parte de um desafio da Nido, abrangendo um frontend e um backend em Docker.

## Tecnologias Utilizadas

- **Linguagens:**
  - JavaScript
  - TypeScript
  - PHP

- **Frameworks:**
  - Laravel (Backend)
  - React (Frontend)

- **Outras Tecnologias:**
  - Docker
  - Vite e Tailwind (para desenvolvimento frontend)
  - MySQL (banco de dados)

# Como Instalar e Usar

## Pré-requisitos

- Docker e Docker Compose instalados.

## Passos para Instalação

### 1. Clone o repositório:

   ```bash
   git clone https://github.com/AntonioEduardo-Dev/Fullstack-Developer-Nido.git
   cd Fullstack-Developer-Nido
   ```

### 2. Navegue até a pasta do backend e inicie o container:

   ```bash
   cd backend
   docker-compose up
   docker-compose up -d (opcional) (para iniciar oculto no terminal)
   ```

### 3. Navegue até a pasta do frontend e inicie o container:

   ```bash
   cd frontend
   docker-compose up
   docker-compose up -d (opcional)
   ```

### 4. Acesse o frontend no seu navegador em http://localhost:5173 e o backend em http://localhost:9000.

### 5. Sobre o script de importar palavras, criei no proprio backend e ao subir o container automaticamente as palavras são importadas.

### Caso queira executar o comando para importar palavras, criei duas opções, primeiramente você deve acessar o container. 

   ### Acessar o container:
   ```bash
    docker exec -it backend_api bash
   ```

   ### Comando para importar em lote (Recomendado):
   ```bash
    php artisan importar:chunkpalavras https://raw.githubusercontent.com/meetDeveloper/freeDictionaryAPI/refs/heads/master/meta/wordList/english.txt 
   ```

   ### Comando para importar por palavras:
   ```bash
    php artisan importar:palavras https://raw.githubusercontent.com/meetDeveloper/freeDictionaryAPI/refs/heads/master/meta/wordList/english.txt 
   ```
   
### 5. Referência
Este projeto foi desenvolvido como um teste da Nido.

### 6. Meus comentários sobre o teste.

  Inicialmente em relação ao backend não tive dúvidas em que tecnologia iria utilizar, decidi pelo que já possuía familiaridade e era o solicitado para a vaga, que era php e laravel.

  De início para não ter problemas com incompatibilidade de versões principalmente do PHP e Composer, optei por utilizar de imediato o Docker, juntamente com o Docker compose, de início pensei em utilizar uma versão mais recente também do mysql, porem como já iria utilizar uma versão mais recente do Laravel para evitar possíveis complicações e consequentemente atrasos inesperados decidi por utilizar o MySQL 5.7, o Laravel não tem muito segredo, escolhi a versão 10 por ser mais atualizada e já ter um tempo rodando, entretanto, utilizar outra versão como a 8, ou a 5 também não seria problema, pois tenho experiencia com elas.

  Ao iniciar o projeto me deparei com uma dúvida, qual ferramenta utilizaria para a gestão de tokens de acesso, inicialmente pensei em utilizar o sanctum, porem ao analisar o readme do teste, rapidamente tive minha resposta, que deveria utilizar o JWT token, o que não me atrapalhou em nada, pois tenho experiencia com essa ferramenta.

  Problema com importação de dados
  Inicialmente ao criar o script para executar as palavras pensei em simplesmente inseri-las, porem logo identifique uma problemática, eram muito dados, e por conta disso a cada palavra uma requisição era chamada, de início pensei em duas soluções a primeira e muito simples armazenar todas as palavras em um array antes de enviar para o banco, e a outra solução ao invés de chamar um insert ou create no caso para cada palavra, usar um insert que enviava uma lista de palavras para o banco, …problema essa lista também ficava pesada para o banco.

  Problema com chunks
  Para resolver o problema do gargalo ao importar palavras no banco, optei por uma solução, criar chunks, mas como nem tudo é flores, deu problema..., para não perder muito tempo fui com a solução simples uma inserção por vez, demorava, porem funcionava.

  Pensamento em inserir dados com filas
  Enquanto estava pensando em resolver o problema dos chunks para a importação, pensei em fazer um cadastro por filas com jobs, mas isso caso tivesse tempo para organizar, pois aumentaria a complexidade do teste (OBS: optei infelizmente por não implementar por que estava muito corrido, e dei prioridade para requisitos funcionais e obrigatórios, mas conseguiria sim implementar, e agora quando estou digitando já resolvi o problema com os chunks e deixei dois tipos de importação)

  Problema com guzzle
  Um dos outros problemas que tive, foi no momento de consultar a API do dicionário, como nunca tinha trabalhado com o guzzle e era um teste, para mim, utilizar ela, optei por não arriscar, pois apesar de ser um problema simples era só tratar suas exceptions, mais uma vez optei por dar prioridade ao obrigatório, e passei a usar o cURL.

  Sobre o decorrer do desenvolvimento com o backend não tive mais problemas grandes e segui com o que eu já sabia e trabalho atualmente.

  Agora vamos para o momento de mais problema, o frontend, aqui resolvi me desafiar apesar de já ter trabalhado com React Native recentemente, React tive poucas experiencias, entretanto, também queria demonstrar que posso aprender e que posso me adaptar a novas tecnologias solicitadas.

  Então. As complicações já surgiram no início para instalar o react, demorei um tempo quebrando a cabeça para me localizar entre os arquivos e como montaria tudo, mas consegui, outra problemática logo no início foram as rotas, não me recordo agora, mas estava com um problema para defini-las e redirecionar para o login caso não logado, sobre os componentes eu tinha alguns exemplos então repliquei.

  Tive alguns problemas com as mensagens de erro no login e cadastro, além de bugs também, que foram corrigidos.

  Com React minha principal problemática foi os estados e tipagem por conta do TypeScript (outra observação: utilizei o TypeScript, pois queria me regrar mais na tipagem, apesar de ainda ter deixado algumas coisas passarem), por algumas vezes os elementos não eram atualizados na hora, então tive que me monitorar mais sobre as chamadas assíncronas e os estados das variáveis, teve um caso que foi até simples de resolver, mas foi complicado para identificar, ao trocar de palavra, o áudio também era alterado consequentemente, porem seu conteúdo não mudava ficando sempre o áudio da primeira palavra, solução identificar o elemento listado no “map” com uma key (no caso o índice do “map”), pois assim o elemento seria diferenciado.

  Enfim, no geral também tive outros problemas com o react, mas no geral foi tranquilo de resolver, espero que meu projeto seja satisfatório, e fico no aguardo de uma resposta, e se possível um feedback, obrigado!!!
  
# Obrigado novamente!, Antonio Eduardo.