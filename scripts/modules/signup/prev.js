export default function prev() {
    this.overlay()
    this.step('back',() => {
        this.overlay()
    })
}