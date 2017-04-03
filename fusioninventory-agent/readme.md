### Arquivo para Deploy do FusionInventory via GPO
Este arquivo contém uma pequena alteração quando se utiliza o arquivo de instalação localizado na rede, utilizando o caminho do tipo UNC "\\\\servidor\\fusioninventory-install\\".

Nesta versão do arquivo, ele copia o arquivo de instalação para o diretório %TEMP% do usuário e dai executa o arquivo.

Variável comentada "SetupOptions" com mais opções disponíveis para instalação do FusionInventory-agent
```
SetupOptions = "/acceptlicense /runnow /server='http://192.168.1.1/glpi/plugins/fusioninventory/' /S /tag=YourTag /delaytime=60 /httpd-trust='127.0.0.1/32,192.168.1.0/24' /execmode=service"
``` 
