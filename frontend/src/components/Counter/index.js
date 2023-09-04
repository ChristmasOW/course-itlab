import React, { useState } from 'react';
import PropTypes from 'prop-types';

export function Counter({ value = 0, minValue = -10, maxValue = 10, padding = 12 }) {
    const [counterValue, setCounterValue] = useState(value);

    const handleIncrement = () => {
        if (counterValue + 1 <= maxValue) {
            setCounterValue(counterValue + 1);
        }
    };

    const handleDecrement = () => {
        if (counterValue - 1 >= minValue) {
            setCounterValue(counterValue - 1);
        }
    };

    return (
        <div style={{ padding, backgroundColor: counterValue < 0 ? '#6ea8fe' : 'yellow' }}>
            <div style={{color: 'black'}}>{counterValue}</div>
            <button onClick={handleIncrement}>+</button>
            <button onClick={handleDecrement}>-</button>
        </div>
    );
}

Counter.propTypes = {
    value: PropTypes.number,
    minValue: PropTypes.number,
    maxValue: PropTypes.number,
    padding: PropTypes.number,
    color: PropTypes.string,
};

export default Counter;