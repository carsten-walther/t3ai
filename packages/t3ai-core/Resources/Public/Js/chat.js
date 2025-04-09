export default class Chat {

    constructor() {
        const output = document.querySelector(resultSelector)
        const form = document.querySelector(formSelector)
        form.addEventListener('submit', async (event) => {
            event.preventDefault()
            const formData = new FormData(event.target)
            await fetch(event.target.action, {
                method: "POST",
                body: formData,
            }).then(async (response) => {
                const json = await response.json();
                await this.loopText(json.result, output)
            })
        })
    }

    async loopText(textParts, textField) {
        for (const [key, value] of Object.entries(textParts)) {
            textField.value = textField.value + value
            await this.timer(await this.getRandomInt(50, 200))
        }
        textField.value = textField.value + '\n\n'
    }

    async getRandomInt(min, max) {
        const minCeil = Math.ceil(min)
        const maxFloor = Math.floor(max)
        return Math.floor(Math.random() * (maxFloor - minCeil) + minCeil)
    }

    async timer(ms) {
        return new Promise(res => setTimeout(res, ms))
    }
}

new Chat()