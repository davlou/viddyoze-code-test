# viddyoze-code-test

This code is a a basic implementation for a shopping basket with key features. A number of assumptions are being made.  

## Features
The following features are implemented:
- Add products to the basket
- Empty the basket
- Apply offers using a offer utility function
- Apply delivery charges using a delivery utility function

## Assumptions
A number of assumptions are being made, they either should be ensured before use or validations should be added as a utility so to not bloat the existing code:
- Data should be sanatised, for example the offer array does not contain multiple time the same offer, or product prices are valid and positive
- Data should passed in the expected format see instantiation example $catalogue, $cataloguePrice, $deliveryRule
