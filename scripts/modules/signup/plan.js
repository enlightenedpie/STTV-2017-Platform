export default function plan(action) {
    var act = action.split('-')
        this.state.customer.plan.id = act[1]
    return this.next(act[0])
}