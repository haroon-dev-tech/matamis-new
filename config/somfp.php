<?php

/**
 * SOMFP line item definitions with hierarchy for Statement of Financial Position
 */
return [
    'sections' => [
        'assets' => [
            'label' => 'Assets',
            'number' => '1',
            'groups' => [
                'fixed_assets' => [
                    'label' => 'Fixed Assets',
                    'number' => '1.1',
                    'items' => [
                        'property_equipment' => 'Property and Equipment',
                    ],
                ],
                'current_assets' => [
                    'label' => 'Current Assets',
                    'number' => '1.2',
                    'items' => [
                        'trade_receivables'      => 'Trade & Other Receivables',
                        'inventory'              => 'Inventory',
                        'security_deposit'       => 'Security Deposit',
                        'employee_advances'      => 'Employee Advances',
                        'prepayments'            => 'Prepayments & Prepaid Expenses',
                        'cash_equivalents'       => 'Cash and Cash Equivalents',
                        'cash_vanissa'           => 'Cash In Hand Vanissa',
                        'cash_omotola'           => 'Cash In Hand Omotola',
                        'bank'                   => 'Bank',
                        'input_vat'              => 'InPut VAT',
                    ],
                ],
            ],
            'total_key' => 'total_assets',
            'total_label' => 'Total Assets (X)',
        ],
        'equity_liabilities' => [
            'label' => 'Equity & Liabilities',
            'number' => '2',
            'groups' => [
                'equity' => [
                    'label' => 'Equity',
                    'number' => '2.1',
                    'items' => [
                        'net_income'                 => 'Net Income',
                        'drawings'                   => 'Drawings',
                        'opening_balance_equity'     => 'Opening Balance Equity',
                        'retained_earnings'          => 'Retained Earnings',
                        'shareholder_current_account' => 'Shareholder Current Account',
                    ],
                ],
                'non_current_liabilities' => [
                    'label' => 'Non-current Liabilities',
                    'number' => '2.2',
                    'items' => [
                        'investment_associates' => 'Investment from Associates',
                    ],
                ],
                'current_liabilities' => [
                    'label' => 'Current Liabilities',
                    'number' => '2.3',
                    'items' => [
                        'accounts_payable'       => 'Accounts Payable (A/P)',
                        'machine_rent_payable'   => 'Machine Rent Payable',
                        'loan_infusion'          => 'Loan from Infusion',
                        'loan_mr_saeed'          => 'Loan From Mr Saeed',
                        'salaries_wages_payable' => 'Salaries & Wages Payable',
                        'transguard_payable'     => 'Transguard Payable',
                        'other_payables'         => 'Other Payables',
                    ],
                ],
            ],
            'total_key' => 'total_equity_liabilities',
            'total_label' => 'Total Equity & Liabilities (Y)',
        ],
    ],
];
