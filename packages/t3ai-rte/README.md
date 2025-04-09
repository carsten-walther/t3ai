# 599media AI RTE (fnn_ai_rte)

Author: Carsten Walther

Date: 2024-07-17

## What does it do?

This extension in
## Installation

Simply install the extension by using a Composer command:

```bash
composer req fnn/fnn-ai-rte
```

## Configuration

If the AI service has been registered, you can create a configuration data record via the list module. This contains the API key, among other things. Further settings are possible depending on the service.

To be able to use AI services in the RTE, a JavaScript module must be loaded and the corresponding button must be set in the RTE yaml configuration.

This is usually done automatically. However, if this does not work, add the following lines to your RTE yaml configuration:

```yaml
editor:
  config:
    importModules:
      - { 'module': '@fnn/fnn-ai-rte/plugin', 'exports': [ 'Chatbot' ] }
    toolbar:
      items:
        - chatbot
  externalPlugins:
    chatbot: { route: 'fnnairte_chatbot_modal' }
```

## Dependencies

This extension depends on fnn_ai_core and registered fnn_ai_xxx service extensions.