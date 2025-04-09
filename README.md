# TYPO3 GenAI - Proof of Concept

## Prerequisites

To run this project, ensure you have the following installed:

* PHP 8.2
* [Composer](https://getcomposer.org/download/)
* [ddev](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/)

## Quickstart

### Build the Project

Start the development environment by running the following command:

```bash
ddev start
```

Then restore the database snapshot with:

```bash
ddev snapshot restore
```

(!) When prompted, choose the "db" snapshot.

### Available URLs

- Backend: https://typo3-ai.dev.local/typo3
    - Username: admin
    - Password: N^tY2MTV

- Frontend: https://typo3-ai.dev.local

- PhpMyAdmin: https://typo3-ai.dev.local:8037

- OpenWeb-UI: http://typo3-ai.dev.local:8080

### Update Composer Dependencies

To update the project's dependencies, run:

```bash
ddev composer update
```

### Install Ollama Models

To install AI models using Ollama, SSH into the container and run one of the following commands:

```bash
ollama run llama:latest
```

or

```bash
ollama run gemma3:1b
```

If you prefer to use a different model, you can browse the available options at [Ollama's model library](https://ollama.com/library).

## Play around

### Frontend

The frontend includes a simple chatbot interface for demonstration purposes.

### Backend

In the TYPO3 backend, you will find a Rich Text Editor (RTE) plugin that enables AI-powered text generation.

### Command Line

There is a Symfony command available to generate alternative text for images. You can execute the following to view available options:

```bash
ddev typo3 t3ai-core:metadata -h
```

## License

GPL-2.0 or later
