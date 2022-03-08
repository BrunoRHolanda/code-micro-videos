# [codeflix] Microserviço de catálogo

Iniciando ambiênte de denvolvimento

```bash
chmod a+x ./.docker/entrypoint.sh
chown user:user ./.docker/entrypoint.sh
docker-compose up -d
```

# Criando chave GPG para assinar commits 
```bash
gpg --full-generate-key # Gere a chave
gpg --list-secret-key --keyid-form LONG # Pegue o id da chave gerada
gpg --edit-key KEY # Adicione outro email se quiser

# $ gpg> adduid -- adiciona outro user a chave
# $ gpg> uid 2 -- seleciona a chave gerada
# $ gpg> trust -- para confiar no usuário
# $ gpg> save -- salva as alterações

gpg --armor --export KEY # Pegue a sua chave e adicione no github

#configurar git
git config --global user.signingkey KEY
git config --global commit.gpgSign true
git config --global tag.gpgSign true

# Adicione essas linhas no seu .bashrc ou .zshrc 
GPG_TTY=$(tty)
export GPG_TTY

source ~/.zshrc
```

## Caso o agente não tenha sido iniciado

```bash
vim ~/.gnupg/gpg.conf # Configure o agente adicione use-agent e salve
gpgconf --launch gpg-agent # Inicie o agente
```
