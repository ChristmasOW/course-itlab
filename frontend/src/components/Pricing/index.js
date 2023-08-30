function Pricing(props) {
    const plans = [
        {
            title: 'Free',
            price: '$0',
            features: ['10 users included', '2 GB of storage', 'Email support', 'Help center access'],
            buttonLabel: 'Sign up for free',
            buttonClass: 'btn-outline-primary'
        },
        {
            title: 'Pro',
            price: '$15',
            features: ['20 users included', '10 GB of storage', 'Priority email support', 'Help center access'],
            buttonLabel: 'Get started',
            buttonClass: 'btn-primary'
        },
        {
            title: 'Enterprise',
            price: '$29',
            features: ['30 users included', '15 GB of storage', 'Phone and email support', 'Help center access'],
            buttonLabel: 'Contact us',
            buttonClass: 'btn-primary'
        }
    ];

    return (
        <div className="row row-cols-1 row-cols-md-3 mb-3 text-center">
            {plans.map((plan, index) => (
                <div className="col" key={index}>
                    <div className={`card mb-4 rounded-3 shadow-sm ${plan.title === 'Enterprise' ? 'border-primary' : ''}`}>
                        <div className={`card-header py-3 ${plan.title === 'Enterprise' ? 'text-bg-primary border-primary' : ''}`}>
                            <h4 className="my-0 fw-normal">{plan.title}</h4>
                        </div>
                        <div className="card-body">
                            <h1 className="card-title pricing-card-title">
                                {plan.price}<small className="text-body-secondary fw-light">/mo</small>
                            </h1>
                            <ul className="list-unstyled mt-3 mb-4">
                                {plan.features.map((feature, index) => (
                                    <li key={index}>{feature}</li>
                                ))}
                            </ul>
                            <button type="button" className={`w-100 btn btn-lg ${plan.buttonClass}`}>
                                {plan.buttonLabel}
                            </button>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
}

export default Pricing;
