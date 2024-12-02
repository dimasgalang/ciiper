<html>
    <head>

    </head>
    <body>
        <table class='table' border="1" width="40%">
            <tr>
                <td><center><img src="{{ public_path('img/Chutex.png') }}" style="width: 60px;"></center></td>
            </tr>
            <tr>
                <th align="center"><b>PT. Chutex International Indonesia</b></th>
            </tr>
        </table>

        <br><br>
        <table class='table' border="1" width="100%">
            <tr>
                <th align="left" width="20%">Employee Number</th>
                <td width="2%">:</td>
                <td align="left" width="30%">65824</td>
                <th align="left" width="20%">Payment Period</th>
                <td>:</td>
                <td align="left" width="30%">Monthly</td>
            </tr>
            <tr>
                <th align="left">Employee Name</th>
                <td>:</td>
                <td align="left">{{ $pegawai[$index]->NAME }}</td>
                <th align="left">Payment Cycle</th>
                <td>:</td>
                <td align="left">January 2025</td>
            </tr>
            <tr>
                <th align="left">Division</th>
                <td>:</td>
                <td align="left">IT</td>
                <th align="left">Printed</th>
                <td>:</td>
                <td align="left">01/01/2025</td>
            </tr>
            <tr>
                <th align="left">Job Title</th>
                <td>:</td>
                <td align="left">Staff</td>
            </tr>
        </table>

        <br><br>
        <table class='table' border="1" width="100%">
            <tr>
                <th align="center" colspan="6">Attendance</th>
            </tr>
            <tr>
                <th align="left">Total Absent</th>
                <td>:</td>
                <td align="left" width="30%">0 Days</td>
                <th align="left">Approved Overtime</th>
                <td>:</td>
                <td align="left" width="30%">2 Hours</td>
            </tr>
            <tr>
                <td colspan="6"><br></td>
            </tr>
            <tr>
                <th align="center" colspan="3">Component</th>
                <th align="center" colspan="3">Amount</th>
            </tr>
            <tr>
                <th align="left">Basic Salary</th>
                <td></td>
                <td align="left"></td>
                <th align="left"></th>
                <td>Rp.</td>
                <td align="right">4,000,000.00</td>
            </tr>
            <tr>
                <th align="left">BPJS - Employment</th>
                <td></td>
                <td align="left"></td>
                <th align="left"></th>
                <td>Rp.</td>
                <td align="right">- 120,000.00</td>
            </tr>
            <tr>
                <th align="left">BPJS - Health Insurance</th>
                <td></td>
                <td align="left"></td>
                <th align="left"></th>
                <td>Rp.</td>
                <td align="right">- 80,000.00</td>
            </tr>
            <tr>
                <th align="left">Approved Overtime</th>
                <td></td>
                <td align="left"></td>
                <th align="left"></th>
                <td>Rp.</td>
                <td align="right">800,000.00</td>
            </tr>
            <tr>
                <th align="left">Absent</th>
                <td></td>
                <td align="left"></td>
                <th align="left"></th>
                <td>Rp.</td>
                <td align="right">- 150,000.00</td>
            </tr>
            <tr>
                <td colspan="6"><br></td>
            </tr>
            <tr>
                <th align="left"></th>
                <td></td>
                <th align="left">Sub Total</th>
                <th align="left"></th>
                <td>Rp.</td>
                <td align="right">4,800,000.00</td>
            </tr>
            <tr>
                <th align="left"></th>
                <td></td>
                <th align="left">Deduction</th>
                <th align="left"></th>
                <td>Rp.</td>
                <td align="right">- 350,000.00</td>
            </tr>
            <tr>
                <td colspan="6"><br></td>
            </tr>
            <tr>
                <th align="left"></th>
                <td></td>
                <th align="right">TOTAL</th>
                <th align="left"></th>
                <td>Rp.</td>
                <td align="right">4,450,000.00</td>
            </tr>
        </table>
    </body>
</html>