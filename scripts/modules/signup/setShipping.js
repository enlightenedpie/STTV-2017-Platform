export default function setShipping(el) {
    this.state.shipping = (el.checked) ? 705 : 0
    this.update()
    return this
}