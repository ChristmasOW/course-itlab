import logo from './logo.svg';
import './App.css';
import Error from './components/Error';
import Product from './components/Product';
import Payment from './components/Payment';
import styled from "styled-components";
import { createBrowserRouter, Link, Outlet, RouterProvider } from "react-router-dom";

const Ul = styled.ul`
  display: flex;
  flex-direction: row;
  list-style: none;
`;

const Li = styled.li`
  color: #f0f0f0;
  font-family: Montserrat;
  font-size: 20px;
  margin: 5px 10px;
`;

let router = createBrowserRouter(
  [
    {
      path: "/", element: <>
        <div>
          <Ul>
            <Li><Link to="/">Home</Link></Li>
            <Li><Link to="/product">Product</Link></Li>
            <Li><Link to="/payment">Payment</Link></Li>
          </Ul>
        </div>
        <Outlet />
      </>,
      children:
        [
          {
            index: true, element:
              <>
                <h1>Home</h1>
                <p>Homepage content
                </p>
              </>
          },
          { path: "product/:productId?", element: <Product /> },
          { path: "payment", element: <Payment /> },
          { path: "*", element: <Error /> },
        ],
      errorElement: <Error />
    }
  ]
)

function App() {
  return (
    <RouterProvider router={router} />
  );
}

export default App;
