# ProfitProject
PHP Exercise – File Processor

Description:
We are going to build a file processor to calculate totals and profit margins. You will be provided a sample file with 4-X columns (sku, cost, price, qty are guaranteed).  We expect an html table outputting the formatted file contents, the profit per product, and the expected profit per product.  The last row of the table should be a summary of information of average cost, average price, total qty, average profit margin, and total profit.

The script must handle the file upload.

Finally, in addition to displaying the total profit for each row and the entire table in USD (default) we also want to add a display of the Total Profit for each row and the entire table in CAD (Canadian dollars) using a real-time look-up.

Three free APIs that can be used for currency conversion:

• Google, https://www.google.com/finance/converter, sample call:

• Yahoo, http://quote.yahoo.com/d/quotes.csv?s={{CURRENCY_FROM}}{{CURRENCY_TO}}=X&f=l1&e=.csv, sample call: http://quote.yahoo.com/d/quotes.csv?s=USDCAD=X&f=l1&e=.csv

• http://fixer.io/, sample call: http://api.fixer.io/latest?base=USD&symbols=CAD


Specifics:

File

• Format: CSV

• Header will always be included, but the order of columns is random.

• Files could include no data (e.g. only a header row).


Processor

• The script that processes the file must be done using object oriented programming, framework is not required.

Table

• Header: SKU, Cost, Price, QTY, Profit Margin, Total Profit (USD), Total Profit (CAD)

• Body: 
    -QTY, profit margin, and total profit have the possibility to be negative.  If these values are negative output the values as red.  If positive, green.
    
• Footer: Average Price, total qty, average profit margin, total profit (USD), total profit (CAD).

Format all dollar values (e.g. $4.55).
