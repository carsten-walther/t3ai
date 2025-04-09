import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import RegularEvent from '@typo3/core/event/regular-event.js';
import Notification from '@typo3/backend/notification.js';

import { default as modalObject } from '@typo3/backend/modal.js';

class Modal {

    initialize() {
        this.editor = modalObject.currentModal.userData.editor;

        const model = this.editor.model;
        const selection = model.document.selection;

        new RegularEvent('click', (evt, targetEl) => {
            evt.preventDefault();

            const textField = document.getElementById('text');
            const promptField = document.getElementById('prompt');
            if (!promptField.value) {
                return;
            }

            let postData = {
                prompt: promptField.value
            };

            const resourceFields = document.querySelector('[name="resourceFields"]').value.split(",");
            for (const [key, fieldName] of Object.entries(resourceFields)) {
                postData[fieldName] = document.querySelector(`[name="${fieldName}"]`).value
            }

            new AjaxRequest(TYPO3.settings.ajaxUrls.t3ai_rte_create)
                .post(postData)
                .then(async function (response) {
                    const resolved = await response.resolve();
                    await Modal.loopText(resolved.result, textField);
                }, function (error) {
                    Notification.error('Error', `The request failed with${error.response.status ? ' ' + error.response.status : ''}: ${error.response.statusText ? error.response.statusText : 'Service not available'}`, 0);
                    modalObject.currentModal.hideModal();
                });
        }).delegateTo(document, '#generateText');

        new RegularEvent('click', (evt, targetEl) => {
            evt.preventDefault();

            const textField = document.getElementById('text');

            if (!textField.value) {
                return;
            }

            model.change((writer) => {
                const insertPosition = selection.getFirstPosition();
                writer.insert(textField.value, insertPosition);
                writer.setSelection(null);
            });

            modalObject.currentModal.hideModal();
        }).delegateTo(document, '#insertText');
    }

    static async loopText(textParts, textField) {
        for (const [key, value] of Object.entries(textParts)) {
            textField.value = textField.value + value;
            await this.timer(await this.getRandomInt(50, 200));
        }
        textField.value = textField.value + '\n\n' + 'Generated with AI.';
    }

    static async getRandomInt(min, max) {
        const minCeiled = Math.ceil(min);
        const maxFloored = Math.floor(max);
        return Math.floor(Math.random() * (maxFloored - minCeiled) + minCeiled);
    }

    static async timer(ms) {
        return new Promise(res => setTimeout(res, ms))
    };

}

const modal = new Modal;
export default modal;
