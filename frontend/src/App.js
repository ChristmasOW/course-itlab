import logo from './logo.svg';
import './App.css';
import { Link, Route, Routes } from 'react-router-dom';
import Error from './components/Error';
import Product from './components/Product';
import Payment from './components/Payment';

function App() {
  return (
    <div>
      <h1>Router Example</h1>
      <p>Navigation</p>
      <ul>
        <li><Link to="/">Home</Link></li>
        <li><Link to="/product">Product</Link></li>
        <li><Link to="/payment">Payment</Link></li>
      </ul>
      <Routes>
        <Route path="/" element={<>
          <h1>Home</h1>
          <p>Homepage content</p>
        </>}
        />
        <Route path="/product/:productId?" element={<Product />} />
        <Route path="/payment" element={<Payment />} />
        <Route path="*" element={<Error />} />
      </Routes>
    </div>
  );
}

export default App;
