export default function report() {
    _st.analytics({
        type : 'ec:setAction',
        action : 'checkout',
        data : {
            'step' : this.state.step
        },
        pageview : true,
        page : '/checkout'
    })
}