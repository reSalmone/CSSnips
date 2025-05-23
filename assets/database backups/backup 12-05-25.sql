PGDMP  .    7                }           postgres    17.4    17.4 #    H           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            I           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            J           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            K           1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false            L           0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4939            �            1259    24679 
   challenges    TABLE     �   CREATE TABLE public.challenges (
    name text NOT NULL,
    description text,
    date_start timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    date_end timestamp without time zone,
    winners text[],
    submissions text[],
    image text
);
    DROP TABLE public.challenges;
       public         heap r       postgres    false            �            1259    24685    comments    TABLE     �   CREATE TABLE public.comments (
    id integer NOT NULL,
    user_id character(1),
    post_id integer,
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);
    DROP TABLE public.comments;
       public         heap r       postgres    false            �            1259    24691    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    218            M           0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    219            �            1259    24692    snips    TABLE     �  CREATE TABLE public.snips (
    id integer NOT NULL,
    creator text,
    views integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    description text,
    element_type character varying(32),
    tags text[],
    file_location character varying(255) NOT NULL,
    likes integer DEFAULT 0,
    saved integer DEFAULT 0,
    variations text[],
    variation_of text,
    challenge_type text
);
    DROP TABLE public.snips;
       public         heap r       postgres    false            �            1259    24701    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    220            N           0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    221            �            1259    24702    users    TABLE     !  CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    likedsnippets text[],
    savedsnippets text[],
    bio text DEFAULT 'This is my bio :)'::text
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    24707    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    222            O           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    223            �           2604    24708    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    219    218            �           2604    24709    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    221    220            �           2604    24710    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    223    222            ?          0    24679 
   challenges 
   TABLE DATA           j   COPY public.challenges (name, description, date_start, date_end, winners, submissions, image) FROM stdin;
    public               postgres    false    217   �(       @          0    24685    comments 
   TABLE DATA           M   COPY public.comments (id, user_id, post_id, content, created_at) FROM stdin;
    public               postgres    false    218   �)       B          0    24692    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variations, variation_of, challenge_type) FROM stdin;
    public               postgres    false    220   �)       D          0    24702    users 
   TABLE DATA           a   COPY public.users (id, username, email, password, likedsnippets, savedsnippets, bio) FROM stdin;
    public               postgres    false    222   �,       P           0    0    comments_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.comments_id_seq', 1, false);
          public               postgres    false    219            Q           0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 35, true);
          public               postgres    false    221            R           0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 3, true);
          public               postgres    false    223            �           2606    24712    challenges challenges_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (name);
 D   ALTER TABLE ONLY public.challenges DROP CONSTRAINT challenges_pkey;
       public                 postgres    false    217            �           2606    24714    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    218            �           2606    24716    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    220            �           2606    24718    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    222            �           2606    24720    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    222            �           2606    24722    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    222            �           2606    24723    comments comments_post_id_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_post_id_fkey FOREIGN KEY (post_id) REFERENCES public.snips(id);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_post_id_fkey;
       public               postgres    false    218    220    4771            �           2606    24728    comments comments_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(username);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_user_id_fkey;
       public               postgres    false    4777    218    222            �           2606    24733    snips snips_creator_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username);
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    220    4777    222            �           2606    24738    snips vincolo_challenge    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT vincolo_challenge FOREIGN KEY (challenge_type) REFERENCES public.challenges(name);
 A   ALTER TABLE ONLY public.snips DROP CONSTRAINT vincolo_challenge;
       public               postgres    false    217    4767    220            ?     x����j�0�����3��e�K�@�J�KL���봾}�Z�B�B��9��`!��+x����k�i�[䄠P��TH�-pHn�ǎh�*�SΥ\+BC �H*WMX@�OalL��NI��g'�?����g���螅KtZ����pc�L�ʚ�yɫ
�@W�"����}���SV���~R�MZ�Z��H5��W�t�s�6gUj�a�"�����`�j�~r��y�M�bS��/�JiW�[����Qd/j�5o��x�N�9���hK[      @      x������ � �      B   �  x��T�n�0�v�½w�m�pPՋV[5i�*��U��^	�`���w�!�Cլ�Cb��������� JŔ_c;�4d$�Ù�	O����re.W�0 �"���~ѓ}�*�nx����� ����~ܷKY=6�+\�6�3�9`�����:�����)
�`�u�\����]��b��?:oV-tS�h�Ve.я�lL�ãqۑ�/+��Dd�'<8�8dAH������r)a�I��Tq0�.��+�5ݸ׹�I�w`i�F��;3ȁʣz�9����vd2���ՠ��ip��+�n�D�b�f&͖��#֨��Cy�H�)9��Y�HITo/�D���h�f[�Uщ������$<tiH��a<��kmB��X���v������>�)1���]w�����DW���F��7^]]��\�\�ނHT1�kѰ�e?yI��E�.��B�+]H@�Y=]+8t}g�3��ךc���7&V������S�!Ƕ�.w0/ ��PX���X	k���Mv,`�ֲ��!�54�.K#�����:i��(]��Z�]�}濽�6���F���r�������m.���s���.x��Y�6i��v4m�N�|��A���$���F�������>�F��N(����:�>�U��T4"�:�}��1��g'�3����c�u      D     x����n�@���,�41�0��]�FR�T��@��#�黗vS�hrsr����Š��r^Ā��!�"�~��2�H��$ѓn�ȝn�&	L[g~ᶲl�u�e�xP�S{�@U�X��SF��M\7�H>/�kJkq��C�ŻaJZǌ��+�ȵc-�b�����G�Rub�i㇅��M���9�_� �]�uG���P���'��������n2SOL���ei}��ڊv� �Cua�>���8s���Ꮤ�h��QB���~-!ޠ _��l     