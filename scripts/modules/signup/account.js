export default function account(action) {
    var obj = this.state.customer.account
    return (!obj.submitted) ? this.update(action,obj) : false
}